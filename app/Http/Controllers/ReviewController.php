<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Tampilkan form ulasan
    public function create(Request $request)
    {
        $bookingId = $request->booking_id;

        // Panggil SP GetBookingById
        $booking = DB::select('CALL GetBookingById(?)', [$bookingId]);

        // Validasi apakah booking ditemukan
        if (empty($booking)) {
            abort(404, 'Booking tidak ditemukan');
        }

        $booking = $booking[0]; // Ambil objek dari array

        // Validasi status dan tanggal check-out
        if ($booking->status !== 'Selesai' || !\Carbon\Carbon::parse($booking->check_out)->isPast()) {
            abort(403, 'Ulasan hanya bisa diberikan setelah check-out selesai.');
        }

        return view('customers.review', compact('booking'));
    }

    // Simpan ulasan
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $bookingId = $request->booking_id;

        // Panggil SP GetBookingById untuk validasi ulang
        $booking = DB::select('CALL GetBookingById(?)', [$bookingId]);

        if (empty($booking)) {
            return back()->withErrors(['error' => 'Booking tidak ditemukan']);
        }

        $booking = $booking[0];

        // Validasi status dan apakah sudah diulas
        if ($booking->status !== 'Selesai' || $booking->reviewed) {
            return back()->withErrors(['error' => 'Ulasan tidak valid.']);
        }

        // Panggil SP InsertReview
        DB::insert('CALL InsertReview(?, ?, ?, ?, ?)', [
            Auth::id(),              // user_id
            $booking->property_id,  // property_id
            $bookingId,             // booking_id
            $request->rating,       // rating
            $request->comment ?? null, // comment
        ]);

        // Panggil SP UpdateBookingReviewStatus
        DB::update('CALL UpdateBookingReviewStatus(?)', [$bookingId]);

        return redirect()->route('riwayat-transaksi.index')->with('success', 'Terima kasih atas ulasannya!');
    }

    public function index()
    {
        $ownerId = Auth::id();

        // Panggil stored procedure dengan parameter ownerId
        $reviews = DB::select('CALL sp_get_owner_reviews(?)', [$ownerId]);

        return view('owner.ulasan', compact('reviews'));
    }

    public function report(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'report_reason' => 'required|string|min:5'
        ]);

        try {
            DB::statement('CALL sp_report_review(?, ?, ?)', [
                $request->review_id,
                $request->report_reason,
                now()
            ]);
            return back()->with('success', 'Review berhasil dilaporkan ke admin. Tunggu tindak lanjut.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melaporkan review: ' . $e->getMessage());
        }
    }
}
