<?php

namespace App\Http\Controllers;

use App\Mail\PengusahaConfirmed;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan statistik dan data booking
     */
    public function showAdminpage()
    {
        try {
            // Data statistik utama
            $stats = $this->getDashboardStats();
            
            // Data booking terbaru untuk tabel
            $bookings = DB::select('CALL getBookingsWithProperty()');
            
            // Data untuk chart booking 7 hari terakhir
            $chartData = $this->getBookingChartData();

            return view('admin.dashboard-admin', array_merge($stats, [
                'bookings' => $bookings,
                'chartLabels' => $chartData['labels'],
                'chartData' => $chartData['values']
            ]));

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat dashboard: '.$e->getMessage());
        }
    }

    /**
     * Mengambil data statistik dashboard
     */
    protected function getDashboardStats()
    {
        return [
            'totalPengusaha' => optional(DB::select('CALL getTotalPengusaha()')[0])->total ?? 0,
            'totalProperty' => optional(DB::select('CALL getTotalProperty()')[0])->total ?? 0,
            'totalBooking' => optional(DB::select('CALL getTotalBooking()')[0])->total ?? 0,
        ];
    }

    /**
     * Mengambil data chart booking 7 hari terakhir
     */
    protected function getBookingChartData()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $bookingData = DB::table('bookings')
            ->select(DB::raw('DATE(check_in) as date'), DB::raw('COUNT(*) as total'))
            ->whereBetween('check_in', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(check_in)'))
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Isi tanggal yang tidak ada data dengan 0
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $dates[$date] = $bookingData[$date] ?? 0;
        }

        return [
            'labels' => array_keys($dates),
            'values' => array_values($dates)
        ];
    }


public function showUsersRole2Pengusaha(Request $request)
{
    $search = $request->input('search', '');

    // Ambil data dari stored procedure
    $usersRaw = DB::select('CALL getUsersByRole2()');

    // Filter manual jika ada search
    if ($search) {
        $searchLower = strtolower($search);
        $usersRaw = array_filter($usersRaw, function ($user) use ($searchLower) {
            return str_contains(strtolower($user->name ?? ''), $searchLower)
                || str_contains(strtolower($user->username ?? ''), $searchLower)
                || str_contains(strtolower($user->email ?? ''), $searchLower);
        });
    }

    // Convert ke Collection
    $usersCollection = collect($usersRaw);

    // Pagination manual
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $perPage = 10;
    $currentPageItems = $usersCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $paginatedUsers = new LengthAwarePaginator(
        $currentPageItems,
        $usersCollection->count(),
        $perPage,
        $currentPage,
        ['path' => LengthAwarePaginator::resolveCurrentPath()]
    );

    // Return view dengan data paginated dan search
    return view('admin.pengusaha', [
        'users' => $paginatedUsers,
        'search' => $search,
    ]);
}


public function showUsersRole3Penyewa(Request $request)
{
    $search = $request->input('search', '');

    $usersRaw = DB::select('CALL getUsersByRole3()');

    // Filter manual jika ada search
    if ($search) {
        $searchLower = strtolower($search);
        $usersRaw = array_filter($usersRaw, function ($user) use ($searchLower) {
            return str_contains(strtolower($user->name ?? ''), $searchLower)
                || str_contains(strtolower($user->username ?? ''), $searchLower)
                || str_contains(strtolower($user->email ?? ''), $searchLower);
        });
    }

    // Convert ke Collection
    $usersCollection = collect($usersRaw);

    // Manual pagination
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $perPage = 10;
    $currentPageItems = $usersCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $paginatedUsers = new LengthAwarePaginator(
        $currentPageItems,
        $usersCollection->count(),
        $perPage,
        $currentPage,
        ['path' => LengthAwarePaginator::resolveCurrentPath()]
    );

    return view('admin.penyewa', [
        'users' => $paginatedUsers,
        'search' => $search,
    ]);
}


    /**
     * Mendapatkan data pengguna dengan pagination
     */
    protected function getPaginatedUsers($procedure, $perPage = 10)
    {
        $usersArray = DB::select($procedure);
        
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
        
        return new LengthAwarePaginator(
            array_slice($usersArray, $offset, $perPage),
            count($usersArray),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Mendapatkan data pengusaha yang belum dikonfirmasi (API)
     */
    public function getUnconfirmed()
    {
        try {
            $users = DB::select("CALL get_unconfirmed_pengusaha()");
            return response()->json(['success' => true, 'data' => $users]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: '.$e->getMessage()
            ], 500);
        }
    }


public function confirmPengusaha(Request $request)
{
    try {
        $request->validate(['user_id' => 'required|integer']);

        $result = DB::select("CALL confirm_pengusaha_account(?)", [$request->user_id]);

        $affectedRows = $result[0]->affected_rows ?? 0;

        if ($affectedRows > 0) {
            $user = User::find($request->user_id);
            if ($user && $user->email) {
                Mail::to($user->email)->send(new PengusahaConfirmed($user));
            }

            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dikonfirmasi dan email notifikasi sudah dikirim.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada perubahan data (user tidak ditemukan atau sudah dikonfirmasi).'
        ], 400);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengkonfirmasi: ' . $e->getMessage()
        ], 500);
    }
}

public function getDetailPengusaha(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id,user_role_id,2'
    ]);

    try {
        $results = DB::select(
            'CALL sp_get_pengusaha_detail(?)', 
            [$request->user_id]
        );

        if (empty($results)) {
            return response()->json([
                'success' => false,
                'message' => 'Akun pengusaha tidak ditemukan.'
            ], 404);
        }

        $pengusaha = $results[0];

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pengusaha->id,
                'name' => $pengusaha->name,
                'username' => $pengusaha->username,
                'email' => $pengusaha->email,
                'email_verified_at' => $pengusaha->email_verified_at,
                'is_confirmed' => (bool)$pengusaha->is_confirmed,
                'is_banned' => (bool)$pengusaha->is_banned,
                'created_at' => $pengusaha->created_at,
                'updated_at' => $pengusaha->updated_at
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mengambil data pengusaha.',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function ban_akun(Request $request)
{
    // Validasi user_id
    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    try {
        // Panggil stored procedure
        $result = DB::select(
            'CALL sp_ban_user(?)', 
            [$request->user_id]
        );

        // Ambil hasil pertama dari stored procedure
        $result = $result[0];

        return response()->json([
            'success' => (bool)$result->success,
            'message' => $result->message
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memblokir pengusaha.',
            'error' => $e->getMessage()
        ], 500);
    }
}


}