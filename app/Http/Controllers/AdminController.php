<?php

namespace App\Http\Controllers;

use App\Mail\PengusahaConfirmed;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{

    public function showAdminpage()
    {
        try {
            // Data statistik utama (sudah termasuk total penyewa)
            $stats = $this->getDashboardStats();

            // Data booking terbaru untuk tabel
            $bookings = DB::select('CALL getBookingsWithProperty()');

            // Data untuk chart booking 7 hari terakhir
            $chartData = $this->getBookingChartData();

            $propertyTypeStats = $this->getPropertyTypeStats();

            $popularProperties = DB::table('bookings')
                ->select(
                    'properties.id',
                    'properties.name',
                    DB::raw('COUNT(bookings.id) as total_booking'),
                    DB::raw('MIN(property_images.images_path) as images_path') // ambil satu gambar, bisa diganti MAX, atau LEFT JOIN LIMIT 1
                )
                ->join('properties', 'bookings.property_id', '=', 'properties.id')
                ->leftJoin('property_images', function($join) {
                    $join->on('properties.id', '=', 'property_images.property_id')
                        ->where('property_images.is_deleted', 0);
                })
                ->groupBy('properties.id', 'properties.name')
                ->orderByDesc('total_booking')
                ->limit(5)
                ->get();

            // Top Vendors (pengusaha dengan properti terbooking terbanyak)
            $topVendors = DB::table('bookings')
                ->select('users.id', 'users.name', DB::raw('COUNT(bookings.id) as total_booking'))
                ->join('properties', 'bookings.property_id', '=', 'properties.id')
                ->join('users', 'properties.user_id', '=', 'users.id')
                ->where('users.user_role_id', 2)
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total_booking')
                ->limit(5)
                ->get();


            return view('admin.dashboard-admin', array_merge($stats, [
                'bookings'    => $bookings,
                'chartLabels' => $chartData['labels'],
                'chartData'   => $chartData['values'],
                'propertyTypeStats' => $propertyTypeStats,
                'popularProperties' => $popularProperties,
                'topVendors' => $topVendors,
            ]));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }

    protected function getPropertyTypeStats()
    {
        $types = DB::table('properties')
            ->select('property_type_id', DB::raw('COUNT(*) as total'))
            ->groupBy('property_type_id')
            ->pluck('total', 'property_type_id')
            ->toArray();

        // Pastikan id = 1 = Homestay, id = 2 = Kost, urutan dan default 0 jika tidak ada
        return [
            'homestay' => $types[1] ?? 0,
            'kost'     => $types[2] ?? 0,
        ];
    }

    /**
     * Mengambil data statistik dashboard (termasuk total penyewa)
     */
    protected function getDashboardStats()
    {
        return [
            'totalPengusaha' => optional(DB::select('CALL getTotalPengusaha()')[0])->total ?? 0,
            'totalProperty'  => optional(DB::select('CALL getTotalProperty()')[0])->total ?? 0,
            'totalBooking'   => optional(DB::select('CALL getTotalBooking()')[0])->total ?? 0,
            // Tambahkan statistik total penyewa di bawah ini
            'totalPenyewa' => optional(DB::select("SELECT COUNT(*) as total FROM users WHERE user_role_id = 3")[0])->total ?? 0,
            // Jika kamu ingin pakai stored procedure:
            // 'totalPenyewa' => optional(DB::select('CALL getTotalPenyewa()')[0])->total ?? 0,
        ];
    }

    /**
     * Mengambil data chart booking 7 hari terakhir
     */
    protected function getBookingChartData()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

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

        // Get users from stored procedure
        $usersRaw = DB::select('CALL getUsersByRole2()');

        // Filter manually if search is provided
        if ($search) {
            $searchLower = strtolower($search);
            $usersRaw = array_filter($usersRaw, function ($user) use ($searchLower) {
                return str_contains(strtolower($user->name ?? ''), $searchLower)
                    || str_contains(strtolower($user->username ?? ''), $searchLower)
                    || str_contains(strtolower($user->email ?? ''), $searchLower);
            });
        }

        // Convert to Collection
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

        // Return view with paginated data and search query
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
                'message' => 'Gagal memuat data: ' . $e->getMessage()
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
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            $results = DB::select('CALL sp_get_pengusaha_detail(?)', [$request->user_id]);

            if (empty($results)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun pengusaha tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $results[0]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDetailPenyewa(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            $results = DB::select('CALL sp_get_penyewa_detail(?)', [$request->user_id]);

            if (empty($results)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun penyewa tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $results[0]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Perbaiki metode ban_akun
    public function ban_akun(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        try {
            $result = DB::select('CALL sp_ban_user(?)', [$request->user_id]);
            $response = $result[0];

            return response()->json([
                'success' => (bool)$response->success,
                'message' => $response->message,
                'is_banned' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memblokir akun: ' . $e->getMessage()
            ], 500);
        }
    }

    // Tambahkan metode unban_akun
    public function unban_akun(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            // Panggil stored procedure untuk membuka blokir pengguna
            $result = DB::select('CALL sp_unban_user(?)', [$request->user_id]);

            // Pastikan hasil sesuai format yang diharapkan
            $response = $result[0];
            return response()->json([
                'success' => (bool)$response->success,
                'message' => $response->message
            ]);
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Unban Akun Error: ' . $e->getMessage());

            // Kirim respons JSON yang benar
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuka blokir Akun.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function homestayProperty_admin(Request $request)
    {
        // Ambil kata kunci pencarian dari query string
        $search = $request->input('search', '');

        // Ambil daftar properti dengan pagination, termasuk pencarian berdasarkan nama properti
        $properties = DB::table('properties')
            ->where('property_type_id', 1)  // Hanya ambil properti dengan type homestay
            ->where('name', 'like', '%' . $search . '%')  // Filter berdasarkan nama properti
            ->paginate(10);  // Hasil per halaman 10

        // Ambil daftar kota
        $cities = DB::select('CALL sp_get_cities()');

        // Tambahkan harga minimum dan nama kota ke masing-masing properti
        foreach ($properties as $property) {
            $result = DB::select("CALL get_MinRoomPriceByProperty(?)", [$property->id]);
            $property->min_price = $result[0]->min_price ?? 0;
            $property->city = $this->getCityNameByPropertyId($property->id); // Ambil nama kota
        }

        // Pass data ke view
        return view('admin.homestay', compact('properties', 'cities', 'search'));
    }

    private function getCityNameByPropertyId($propertyId)
    {
        // Query untuk mengambil nama kota berdasarkan relasi dengan subdistrict, district, dan city
        $city = DB::select("
        SELECT c.city_name
        FROM cities c
        INNER JOIN districts d ON c.id = d.city_id  -- Menghubungkan cities dan districts
        INNER JOIN subdistricts s ON d.id = s.dist_id  -- Menghubungkan districts dan subdistricts
        INNER JOIN properties p ON s.id = p.subdis_id  -- Menghubungkan subdistricts dan properties
        WHERE p.id = ?", [$propertyId]);

        // Kembalikan nama kota atau null jika tidak ditemukan
        return $city ? $city[0]->city_name : null;
    }

    public function kostProperty_admin(Request $request)
    {
        // Ambil kata kunci pencarian dari query string
        $search = $request->input('search', '');

        // Ambil daftar properti dengan pagination, termasuk pencarian berdasarkan nama properti
        $properties = DB::table('properties')
            ->where('property_type_id', 2)  // Hanya ambil properti dengan type homestay
            ->where('name', 'like', '%' . $search . '%')  // Filter berdasarkan nama properti
            ->paginate(10);  // Hasil per halaman 10

        // Ambil daftar kota
        $cities = DB::select('CALL sp_get_cities()');

        // Tambahkan harga minimum dan nama kota ke masing-masing properti
        foreach ($properties as $property) {
            $result = DB::select("CALL get_MinRoomPriceByProperty(?)", [$property->id]);
            $property->min_price = $result[0]->min_price ?? 0;
            $property->city = $this->getCityNameByPropertyId($property->id); // Ambil nama kota
        }

        // Pass data ke view
        return view('admin.homestay', compact('properties', 'cities', 'search'));
    }

    public function index(): View
    {
        // Mengambil semua tipe property menggunakan stored procedure
        $propertyTypes = DB::select('CALL GetPropertyTypes()');
        return view('admin.tipe-property', compact('propertyTypes'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'property_type' => 'required|max:255',
            'description' => 'nullable'
        ]);

        // Menyimpan data baru menggunakan stored procedure
        DB::statement('CALL CreatePropertyType(?, ?)', [
            $request->input('property_type'),
            $request->input('description') ?? null,
        ]);

        return redirect()->route('admin.tipe_property.index')->with('success', 'Tipe property berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Mengambil data untuk edit berdasarkan ID
        $type = DB::selectOne('CALL GetPropertyTypeById(?, @OUT_STATUS)', [$id]);

        // Memeriksa apakah data ditemukan
        if ($type) {
            return response()->json($type);  // Mengembalikan data tipe properti dalam format JSON
        } else {
            return response()->json(['message' => 'Tipe properti tidak ditemukan'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'property_type' => 'required|max:255',
            'description' => 'nullable'
        ]);

        // Mengupdate data tipe properti menggunakan stored procedure
        DB::statement('CALL UpdatePropertyType(?, ?, ?)', [
            $id,
            $request->input('property_type'),
            $request->input('description') ?? null,
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.tipe_property.index')->with('success', 'Tipe property berhasil diperbarui!');
    }


    public function destroy($id)
    {
        // Menghapus data tipe properti menggunakan stored procedure
        DB::statement('CALL DeletePropertyType(?)', [$id]);
        return redirect()->route('admin.tipe_property.index')->with('success', 'Tipe property berhasil dihapus!');
    }

    // Method lama yang mengembalikan view full-page
    public function showDetailProperty($id)
    {
        $propertyResult = DB::select('CALL view_propertyById(?)', [$id]);
        if (empty($propertyResult)) {
            abort(404, 'Properti tidak ditemukan');
        }
        $property = $propertyResult[0];
        $locationData = optional(DB::select('CALL get_fullLocation(?)', [$property->subdis_id]))[0] ?? null;
        $images    = DB::select('CALL get_propertyImagesByProperty(?)', [$id]);
        $fasilitas = DB::select('CALL get_propertyFacilitiesByProperty(?)', [$id]);
        $rooms     = DB::select('CALL get_RoomsByPropertyId(?)', [$id]);
        $property_roomPrice = DB::select('CALL get_MinRoomPriceByProperty(?)', [$id]);
        $reviews   = DB::select('CALL get_PropertyReviews(?)', [$id]);
        $ratingData    = DB::select('CALL get_AverageRating(?)', [$id]);
        $avgRating     = $ratingData[0]->avg_rating ?? 0;
        $totalReviews  = $ratingData[0]->total_reviews ?? 0;

        return view('admin/detail-homestay', compact(
            'property',
            'images',
            'locationData',
            'rooms',
            'property_roomPrice',
            'fasilitas',
            'reviews',
            'avgRating',
            'totalReviews'
        ));
    }

    // Method baru untuk AJAX (mengembalikan JSON)
    public function detailAjax($id)
    {
        $propertyResult = DB::select('CALL view_propertyById(?)', [$id]);
        if (empty($propertyResult)) {
            return response()->json([
                'success' => false,
                'message' => 'Properti tidak ditemukan'
            ], 404);
        }
        $property        = $propertyResult[0];
        $locationData    = optional(DB::select('CALL get_fullLocation(?)', [$property->subdis_id]))[0] ?? null;
        $images          = DB::select('CALL get_propertyImagesByProperty(?)', [$id]);
        $fasilitas       = DB::select('CALL get_propertyFacilitiesByProperty(?)', [$id]);
        $rooms           = DB::select('CALL get_RoomsByPropertyId(?)', [$id]);
        $property_roomPrice = DB::select('CALL get_MinRoomPriceByProperty(?)', [$id]);
        $reviews         = DB::select('CALL get_PropertyReviews(?)', [$id]);
        $ratingData      = DB::select('CALL get_AverageRating(?)', [$id]);
        $avgRating       = $ratingData[0]->avg_rating ?? 0;
        $totalReviews    = $ratingData[0]->total_reviews ?? 0;

        return response()->json([
            'success'            => true,
            'property'           => $property,
            'locationData'       => $locationData,
            'images'             => $images,
            'fasilitas'          => $fasilitas,
            'rooms'              => $rooms,
            'property_roomPrice' => $property_roomPrice,
            'reviews'            => $reviews,
            'avgRating'          => $avgRating,
            'totalReviews'       => $totalReviews,
        ]);
    }

    public function bookingsAjax($id)
    {
        // Misal: panggil stored procedure atau query Eloquent untuk ambil daftar booking
        $bookings = DB::select('CALL get_BookingsByProperty(?)', [$id]);
        // Ambil nama properti singkat (bisa dari $bookings atau panggil lagi view_propertyById)
        $prop = DB::select('CALL view_propertyById(?)', [$id])[0] ?? null;
        $property_name = $prop->property_name ?? '—';

        return response()->json([
            'property_name' => $property_name,
            'bookings' => $bookings
        ]);
    }

    // AdminController.php
    public function bookingDetailAjax($id)
    {
        // 1. Ambil tabel bookings
        $bookingRaw = DB::select('CALL get_BookingById(?)', [$id]);
        if (empty($bookingRaw)) {
            return response()->json(['error' => 'Booking tidak ditemukan'], 404);
        }
        $booking = (array) $bookingRaw[0];

        // 2. Ambil detail booking (booking_details)
        $detailsRaw = DB::select('CALL get_BookingDetailsByBooking(?)', [$id]);
        // Misal stored procedure mengembalikan kolom: room_type_name, quantity, price_per_room, subtotal
        $details = array_map(function ($r) {
            return (array) $r;
        }, $detailsRaw);

        // 3. Field “notes” (jika ada) – sesuaikan kolom di tabel booking
        // Jika tidak ada, mengembalikan string kosong atau “-”
        $booking['notes'] = $booking['notes'] ?? '';

        return response()->json([
            'booking' => $booking,
            'details' => $details
        ]);
    }

    public function allReviews(Request $request)
    {
        // Keyword pencarian (property name atau reviewer)
        $search = $request->input('search');

        // Stored procedure harus menerima parameter keyword ('' atau null artinya semua)
        $raw = DB::select('CALL get_AllReviewsBySearch(?)', [$search]);
        // Misal SP mengembalikan kolom:
        // property_name, reviewer_name, rating, comment, created_at

        // Konversi hasil ke array asosiatif
        $reviews = array_map(function ($r) {
            return (array) $r;
        }, $raw);

        return view('admin.reviews', compact('reviews', 'search'));
    }

    public function hide(Request $request, $id)
    {
        try {
            DB::statement('CALL sp_hide_review(?)', [$id]);
            return back()->with('success', 'Komentar berhasil disembunyikan.');
        } catch (\Exception $e) {
            // Pesan error, bisa custom atau ambil pesan dari exception
            return back()->with('error', 'Gagal menyembunyikan komentar: ' . $e->getMessage());
        }
    }

    public function unhide(Request $request, $id)
    {
        try {
            DB::statement('CALL sp_unhide_review(?)', [$id]);
            return back()->with('success', 'Komentar berhasil ditampilkan kembali.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menampilkan komentar: ' . $e->getMessage());
        }
    }

    public function show()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }
}
