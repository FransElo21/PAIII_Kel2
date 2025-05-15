<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

public function showAdminpage()
{
    // Data statistik lain
    $totalPengusahaResult = DB::select('CALL getTotalPengusaha()');
    $totalPropertyResult = DB::select('CALL getTotalProperty()');
    $totalBookingResult = DB::select('CALL getTotalBooking()');

    $totalPengusaha = $totalPengusahaResult[0]->total ?? 0;
    $totalProperty = $totalPropertyResult[0]->total ?? 0;
    $totalBooking = $totalBookingResult[0]->total ?? 0;

    // Data booking terbaru untuk tabel
    $bookings = DB::select('CALL getBookingsWithProperty()');

    // Ambil data booking per hari untuk 7 hari terakhir
    $startDate = Carbon::now()->subDays(6)->startOfDay(); // 6 hari lalu + hari ini total 7 hari
    $endDate = Carbon::now()->endOfDay();

    $bookingPerHariRaw = DB::table('bookings')
        ->select(DB::raw('DATE(check_in) as date'), DB::raw('COUNT(*) as total_booking'))
        ->whereBetween('check_in', [$startDate, $endDate])
        ->groupBy(DB::raw('DATE(check_in)'))
        ->orderBy('date')
        ->get();

    // Buat array tanggal dan jumlah booking untuk chart
    $dates = [];
    $totals = [];

    // Buat semua tanggal 7 hari terakhir dulu dengan default 0
    for ($i = 0; $i < 7; $i++) {
        $date = $startDate->copy()->addDays($i)->format('Y-m-d');
        $dates[$date] = 0;
    }

    // Isi data hasil query ke array tanggal
    foreach ($bookingPerHariRaw as $item) {
        $dates[$item->date] = $item->total_booking;
    }

    // Pisah key dan value untuk chart js
    $chartLabels = array_keys($dates);
    $chartData = array_values($dates);

    return view('admin.dashboard-admin', compact(
        'totalPengusaha', 'totalProperty', 'totalBooking', 'bookings',
        'chartLabels', 'chartData'
    ));
}

public function showUsersRole2Pengusaha()
    {
        // Panggil stored procedure
        $users = DB::select('CALL getUsersByRole2()');

        // Kirim data ke view
        return view('admin.pengusaha', compact('users'));
    }

    public function showUsersRole3Penyewa()
    {
        // Panggil stored procedure
        $users = DB::select('CALL getUsersByRole3()');

        // Kirim data ke view
        return view('admin.penyewa', compact('users'));
    }

}
