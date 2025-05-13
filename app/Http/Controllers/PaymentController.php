<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false; // true jika production
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function payment_show($booking_id)
    {
        $bookingDetails = DB::select('CALL get_BookingDetails(?)', [$booking_id]);

        if (empty($bookingDetails)) {
            abort(404);
        }

        $booking = $bookingDetails[0];

        return view('customers.pembayaran', compact('booking'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|string',
            'guest_name' => 'required|string',
            'email' => 'required|email',
            'total_price' => 'required|numeric|min:1'
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => 'BOOKING-' . $request->input('booking_id'),
                'gross_amount' => (int)$request->input('total_price'),
            ],
            'customer_details' => [
                'first_name' => $request->input('guest_name'),
                'email' => $request->input('email'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success($bookingId)
    {
        return view('payments.success', compact('bookingId'));
    }

    public function notification(Request $request)
    {
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id;

        // Ambil booking ID dari order_id
        $bookingId = str_replace('BOOKING-', '', $orderId);

        // Simpan ke database (misalnya update status booking)
        // Contoh sederhana:
        DB::table('bookings')
            ->where('id', $bookingId)
            ->update(['status' => match ($transaction) {
                'capture', 'settlement' => 'paid',
                'pending' => 'pending',
                default => 'failed'
            }]);

        return response()->json(['status' => 'ok']);
    }
}