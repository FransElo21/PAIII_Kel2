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
    public function payment_show(Request $request, $booking_id)
    {
        // Ambil detail booking dari stored procedure
        $bookingDetails = DB::select('CALL get_BookingDetails(?)', [$booking_id]);

        if (empty($bookingDetails)) {
            abort(404);
        }

        // Baris pertama untuk summary booking
        $booking = $bookingDetails[0];

        // Semua baris untuk detail kamar
        $rooms = $bookingDetails;

        // Cek kadaluarsa booking
        $result = DB::select('CALL check_booking_expiration(?)', [$booking_id]);
        if ($result && $result[0]->status === 'Kadaluarsa') {
            return redirect()->route('landingpage')->with('error', 'Booking sudah kadaluarsa.');
        }

        // Ambil snap_token dari query param (yang dikirim dari store_bokings)
        $snapToken = $request->query('snap_token', null);

        // Kirim data ke Blade
        return view('customers.pembayaran', compact('booking', 'rooms', 'snapToken'));
    }

    public function process(Request $request)
    {
        // Validasi
        $request->validate([
            'booking_id'  => 'required|string',
            'guest_name'  => 'required|string',
            'email'       => 'required|email',
            'total_price' => 'required|numeric|min:1',
        ]);

        $bookingId = $request->input('booking_id');

        // (Ambil booking & cek expiration seperti sebelumnya…)

        // Konfigurasi Midtrans
        Config::$serverKey    = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $params = [
            'transaction_details' => [
                'order_id'     => 'BOOKING-' . $bookingId,
                'gross_amount' => (int)$request->input('total_price'),
            ],
            'customer_details' => [
                'first_name' => $request->input('guest_name'),
                'email'      => $request->input('email'),
            ],
            'enabled_payments' => ['gopay','bank_transfer'],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            // Simpan token ke DB
            DB::statement('CALL update_snap_token(?, ?)', [$bookingId, $snapToken]);

            // Kembalikan JSON berisi token
            return response()->json(['snapToken' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function success($booking_id)
    {
        $bookingDetails = DB::select('CALL get_BookingDetails(?)', [$booking_id]);
        if (empty($bookingDetails)) {
            abort(404);
        }
        $booking = $bookingDetails[0];

        return view('payments.success', compact('booking', 'booking_id'));
    }

    public function cancel(Request $request, $bookingId)
    {
        // 1. Ambil data booking dari tabel menggunakan Query Builder
        $booking = DB::table('bookings')
            ->where('id', $bookingId)
            ->first();

        // 2. Jika tidak ditemukan, lempar 404
        if (! $booking) {
            abort(404, 'Booking tidak ditemukan');
        }

        // 3. Cek status hanya boleh batalkan jika masih 'pending'
        //    (pastikan di database status defaultnya ditulis 'pending')
        if ($booking->status !== 'Belum Dibayar') {
            return redirect()
                ->route('payment.show', $bookingId)
                ->with('error', 'Pembayaran tidak dapat dibatalkan.');
        }

        try {
            // 4. Panggil Stored Procedure cancel_payment (yang meng‐null url_pembayaran dan ubah status menjadi 'Dibatalkan')
            DB::statement('CALL cancel_payment(?)', [$bookingId]);

            // 5. Redirect kembali dengan pesan sukses
            return redirect()
                ->route('payment.show', $bookingId)
                ->with('success', 'Pembayaran berhasil dibatalkan. Silakan pilih ulang metode pembayaran.');
        } 
        catch (\Exception $e) {
            // 6. Log error detail untuk debugging
            Log::error("Gagal membatalkan pembayaran booking #{$bookingId}: " . $e->getMessage());

            return redirect()
                ->route('payment.show', $bookingId)
                ->with('error', 'Terjadi kesalahan saat membatalkan pembayaran.');
        }
    }


    // public function notification(Request $request)
    // {
    //     Config::$serverKey = config('services.midtrans.serverKey');
    //     Config::$isProduction = config('services.midtrans.isProduction', false);
    //     Config::$isSanitized = true;
    //     Config::$is3ds = true;

    //     $notif = new Notification();

    //     $transaction = $notif->transaction_status;
    //     $orderId = $notif->order_id;
    //     $bookingId = str_replace('BOOKING-', '', $orderId);

    //     $status = match ($transaction) {
    //         'capture', 'settlement' => 'success',
    //         'pending' => 'pending',
    //         'deny', 'cancel', 'expire' => 'cancelled',
    //         default => 'failed',
    //     };

    //     DB::statement('CALL update_booking_status(?, ?)', [$bookingId, $status]);

    //     return response()->json(['status' => 'ok']);
    // }

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
