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
        // 1. Konfigurasi Midtrans
        Config::$serverKey    = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        try {
            // 2. Terima notification dari Midtrans
            $notification = new Notification();

            // 3. Ambil data penting
            $transactionStatus = $notification->transaction_status;
            $paymentType       = $notification->payment_type;
            $fraudStatus       = $notification->fraud_status;
            $orderId           = $notification->order_id;

            // 4. Ekstrak booking ID
            $bookingId = str_replace('BOOKING-', '', $orderId);

            // 5. Verifikasi booking ada
            $exists = DB::table('bookings')->where('id', $bookingId)->exists();
            if (! $exists) {
                Log::error('Booking not found', ['booking_id' => $bookingId]);
                return response()->json([
                    'meta' => ['code' => 404, 'message' => 'Booking not found']
                ], 404);
            }

            // 6. Tentukan status baru
            $status = match ($transactionStatus) {
                'capture'   => ($paymentType === 'credit_card' && $fraudStatus === 'challenge')
                                ? 'Belum Dibayar'
                                : 'Berhasil',
                'settlement'=> 'Berhasil',
                'pending'   => 'Belum Dibayar',
                'deny', 'cancel', 'expire' => 'Dibatalkan',
                default     => 'Gagal',
            };

            // 7. Update via Stored Procedure
            DB::statement('CALL update_booking_status1(?, ?)', [
                $bookingId,
                $status
            ]);

            Log::info('Booking status updated via SP', [
                'booking_id' => $bookingId,
                'new_status' => $status
            ]);

            // 8. Respon balik ke Midtrans
            return response()->json([
                'meta' => ['code' => 200, 'message' => 'Callback processed successfully']
            ]);
        }
        catch (\Exception $e) {
            Log::error('Midtrans Callback Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'meta' => [
                    'code'    => 500,
                    'message' => 'Callback processing failed',
                    'error'   => $e->getMessage()
                ]
            ], 500);
        }
    }
}
