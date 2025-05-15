<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
    public function callback()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;

            $bookingId = str_replace('BOOKING-', '', $orderId);

            $booking = DB::select('CALL get_BookingDetails(?)', [$bookingId]);

            if (empty($booking)) {
                return response()->json([
                    'meta' => [
                        'code' => 404,
                        'message' => 'Booking not found'
                    ]
                ], 404);
            }

            $booking = $booking[0];

            Log::info('Midtrans Callback Received', [
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'order_id' => $orderId,
                'booking_id' => $bookingId,
            ]);

            $statusPembayaran = match ($transactionStatus) {
                'capture' => ($paymentType === 'credit_card' && $fraudStatus === 'challenge') ? 'pending' : 'success',
                'settlement' => 'success',
                'pending' => 'pending',
                'deny', 'cancel', 'expire' => 'cancelled',
                default => 'failed',
            };

            DB::statement('CALL update_booking_status(?, ?)', [$bookingId, $statusPembayaran]);

            // Jika sudah success dan status sebelumnya bukan success, update properti dan unit
            if ($statusPembayaran === 'success' && $booking->status !== 'success') {
                // Jika perlu lakukan logika tambahan di sini
                $property = DB::select('CALL get_property_details(?)', [$booking->property_id]);
                if ($property && $property[0]->unit_available > 0) {
                    DB::statement('CALL decrement_property_units(?)', [$booking->property_id]);
                }
            }
            Log::info('Midtrans Payload', (array) $notification);

            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans Notification Processed Successfully'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'meta' => [
                    'code' => 500,
                    'message' => 'Midtrans Notification Failed',
                    'error' => $e->getMessage(),
                ]
            ], 500);
        }
    }
}
