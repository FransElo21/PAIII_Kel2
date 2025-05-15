<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function payment_show($booking_id)
    {
        // Ambil detail booking dari stored procedure
        $bookingDetails = DB::select('CALL get_BookingDetails(?)', [$booking_id]);

        if (empty($bookingDetails)) {
            abort(404);
        }

        $booking = $bookingDetails[0];

        // Cek kadaluarsa booking
        $result = DB::select('CALL check_booking_expiration(?)', [$booking_id]);
        if ($result && $result[0]->status === 'expired') {
            return redirect()->route('front.index')->with('error', 'Booking sudah kadaluarsa.');
        }

        return view('customers.pembayaran', compact('booking'));
    }

    public function process(Request $request)
    {
        // Validasi input
        $request->validate([
            'booking_id' => 'required|string',
            'guest_name' => 'required|string',
            'email' => 'required|email',
            'total_price' => 'required|numeric|min:1',
        ]);

        $booking_id = $request->input('booking_id');

        // Ambil booking
        $bookingDetails = DB::select('CALL get_BookingDetails(?)', [$booking_id]);
        if (empty($bookingDetails)) {
            return response()->json(['error' => 'Data booking tidak ditemukan.'], 404);
        }
        $booking = $bookingDetails[0];

        // Cek kadaluarsa
        $result = DB::select('CALL check_booking_expiration(?)', [$booking_id]);
        if ($result && $result[0]->status === 'expired') {
            return response()->json(['error' => 'Booking sudah kadaluarsa.'], 400);
        }

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $totalPrice = (int) $request->input('total_price');

        $orderId = 'BOOKING-' . $booking_id; // Konsisten pakai prefix

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->input('guest_name'),
                'email' => $request->input('email'),
            ],
            'enabled_payments' => ['gopay', 'bank_transfer'],
            'callbacks' => [
                'finish' => route('payment.success', ['bookingId' => $booking_id]),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan snap token (bukan URL) ke DB via stored procedure
            DB::statement('CALL update_snap_token(?, ?)', [$booking_id, $snapToken]);

            // Redirect ke halaman pembayaran Midtrans (sandbox/production otomatis)
            $redirectUrl = Config::$isProduction
                ? "https://app.midtrans.com/snap/v2/vtweb/{$snapToken}"
                : "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}";

            return redirect($redirectUrl);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat transaksi pembayaran: ' . $e->getMessage()], 500);
        }
    }

    public function success($bookingId)
    {
        $bookingDetails = DB::select('CALL get_BookingDetails(?)', [$bookingId]);
        if (empty($bookingDetails)) {
            abort(404);
        }
        $booking = $bookingDetails[0];

        return view('payments.success', compact('booking', 'bookingId'));
    }

    public function cancel($bookingId)
    {
        // Batalkan pembayaran dan hapus token via stored procedure
        DB::statement('CALL cancel_payment(?)', [$bookingId]);

        return redirect()->route('payment_show', $bookingId)
            ->with('success', 'Pembayaran dibatalkan. Silakan pilih ulang metode pembayaran.');
    }

    public function notification(Request $request)
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id;
        $bookingId = str_replace('BOOKING-', '', $orderId);

        $status = match ($transaction) {
            'capture', 'settlement' => 'success',
            'pending' => 'pending',
            'deny', 'cancel', 'expire' => 'cancelled',
            default => 'failed',
        };

        DB::statement('CALL update_booking_status(?, ?)', [$bookingId, $status]);

        return response()->json(['status' => 'ok']);
    }

    public function cetakResi($bookingId)
    {
        $bookingDetails = DB::select('CALL get_BookingDetails(?)', [$bookingId]);
        if (empty($bookingDetails)) {
            abort(404);
        }

        $booking = $bookingDetails[0];

        $pdf = Pdf::loadView('pdf.resi', compact('booking'));
        return $pdf->download('resi_booking_' . $bookingId . '.pdf');
    }

    
}
