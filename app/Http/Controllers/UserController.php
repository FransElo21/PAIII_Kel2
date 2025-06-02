<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;

class UserController extends Controller
{
    public function showDetailProperty($id)
    {
        // 1. Ambil detail properti
        $propertyResult = DB::select('CALL view_propertyById(?)', [$id]);

        // 2. Jika properti tidak ditemukan
        if (empty($propertyResult)) {
            abort(404, 'Properti tidak ditemukan');
        }

        // 3. Ambil properti pertama
        $property = $propertyResult[0];

        // 4. Ambil lokasi lengkap
        $locationData = optional(DB::select('CALL get_fullLocation(?)', [$property->subdis_id]))[0] ?? null;

        // 5. Ambil gambar-gambar properti
        $images = DB::select('CALL get_propertyImagesByProperty(?)', [$id]);

        // 6. Ambil fasilitas properti
        $fasilitas = DB::select('CALL get_propertyFacilitiesByProperty(?)', [$id]);

        // 7. Ambil kamar dan harga
        $rooms = DB::select("CALL get_RoomsByPropertyId(?)", [$id]);
        $property_roomPrice = DB::select("CALL get_MinRoomPriceByProperty(?)", [$id]);

        // 8. Ambil ulasan
        $reviews = DB::select("CALL get_PropertyReviews(?)", [$id]);

        // 9. Hitung rating rata-rata
        $ratingData = DB::select("CALL get_AverageRating(?)", [$id]);
        $avgRating = $ratingData[0]->avg_rating ?? 0;
        $totalReviews = $ratingData[0]->total_reviews ?? 0;

        // 10. Kirim ke view
        return view('customers/detail-property', compact(
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

    public function homestayProperty() {
        $properties = DB::select('CALL view_propertiesByType(?)', ['1']);
        $cities = DB::select('CALL sp_get_cities()');

         // Loop untuk menambahkan harga kamar termurah ke setiap properti
         foreach ($properties as $property) {
            // Panggil SP untuk properti saat ini
            $result = DB::select("CALL get_MinRoomPriceByProperty(?)", [$property->property_id]);
            
            // Tambahkan hasil ke objek properti
            $property->min_price = $result[0]->min_price ?? 0;
        }

        return view('customers/homestay', compact('properties', 'cities'));
    }

    public function search_homestay(Request $request)
    {
        $keyword = $request->input('keyword');
        $cityId = $request->input('city_id');
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $orderBy = $request->input('order_by');

        // Mengambil hasil pencarian properti hanya yang tipe 1 (homestay)
        $properties = DB::select("CALL sp_search_propertytest(?, ?, ?, ?, ?, ?)", [
            $keyword,
            $cityId,
            $priceMin,
            $priceMax,
            $orderBy,
            1 // Filter untuk property type 1 (homestay)
        ]);

        // Mengambil daftar kota
        $cities = DB::select('CALL sp_get_cities()');

        // Mengirimkan data ke view
        return view('customers.hasil-search-homestay', compact('properties', 'cities'));
    }

    public function kostProperty() {
        $properties = DB::select('CALL view_propertiesByType(?)', ['2']);
        $cities = DB::select('CALL sp_get_cities()');

         // Loop untuk menambahkan harga kamar termurah ke setiap properti
         foreach ($properties as $property) {
            // Panggil SP untuk properti saat ini
            $result = DB::select("CALL get_MinRoomPriceByProperty(?)", [$property->property_id]);
            
            // Tambahkan hasil ke objek properti
            $property->min_price = $result[0]->min_price ?? 0;
        }
    
        return view('customers/kost', compact('properties','cities'));
    }

    public function search_kost(Request $request)
    {
        $keyword = $request->input('keyword');
        $cityId = $request->input('city_id');
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $orderBy = $request->input('order_by');

        // Mengambil hasil pencarian properti hanya yang tipe 1 (homestay)
        $properties = DB::select("CALL sp_search_propertytest(?, ?, ?, ?, ?, ?)", [
            $keyword,
            $cityId,
            $priceMin,
            $priceMax,
            $orderBy,
            2 // Filter untuk property type 1 (homestay)
        ]);

        // Mengambil daftar kota
        $cities = DB::select('CALL sp_get_cities()');

        // Mengirimkan data ke view
        return view('customers.hasil-search-kost', compact('properties', 'cities'));
    }

    public function ajukanSewa($property_id) {
        $property = DB::select('CALL view_propertyById(?)', [$property_id]);
        $fasilitas = DB::select('CALL get_propertyFacilitiesByProperty(?)', [$property_id]);
        $rooms = DB::select("CALL get_RoomsByPropertyId(?)", [$property_id]);
        $images = DB::select('CALL get_propertyImagesByProperty(?)', [$property_id]);
    
        if (empty($property)) {
            abort(404);
        }
    
        return view('customers.pengajuan-sewa', [
            'property' => $property[0],
            'fasilitas' => $fasilitas,
            'rooms' => $rooms,
            'images' => $images
        ]);
    }    
         

public function pemesanan(Request $request)
{
    $propertyId = $request->input('property_id');
    $bookingData = $request->input('booking_data');
    $roomsInput = json_decode($bookingData, true);

    if (!$propertyId || !$roomsInput || count($roomsInput) == 0) {
        return back()->with('error', 'Data pemesanan tidak lengkap.');
    }

    $checkIn = $roomsInput[0]['check_in'] ?? null;
    $checkOut = $roomsInput[0]['check_out'] ?? null;

    // Validasi tanggal
    if (!$checkIn || !$checkOut) {
        return back()->with('error', 'Tanggal check-in/check-out belum diisi.');
    }
    if ($checkOut <= $checkIn) {
        return back()->with('error', 'Tanggal check-out harus setelah check-in!');
    }

    $property = DB::select('CALL view_propertyById(?)', [$propertyId]);
    $rooms = collect(DB::select('CALL get_RoomsByPropertyId(?)', [$propertyId]))->keyBy('room_id');

    $selectedRooms = [];
    $totalPrice = 0;

    $duration = \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut));
    if ($duration < 1) {
        return back()->with('error', 'Durasi inap minimal 1 malam.');
    }

    foreach ($roomsInput as $item) {
        $roomId = $item['room_id'];
        $qty = intval($item['quantity']);
        if ($qty > 0) {
            if (!isset($rooms[$roomId])) return back()->with('error', 'Kamar tidak ditemukan.');
            if ($qty > $rooms[$roomId]->available_room) return back()->with('error', 'Stok kamar tidak cukup.');
            $subtotal = $rooms[$roomId]->latest_price * $qty * $duration;
            $totalPrice += $subtotal;
            $selectedRooms[] = [
                'room_id' => $roomId,
                'room_type' => $rooms[$roomId]->room_type,
                'quantity' => $qty,
                'price_per_room' => $rooms[$roomId]->latest_price,
                'subtotal' => $subtotal,
            ];
        }
    }

    if (count($selectedRooms) == 0) {
        return back()->with('error', 'Pilih minimal 1 kamar.');
    }

    session([
        'booking.selected_rooms' => $selectedRooms,
        'booking.property_id' => $propertyId,
        'booking.check_in' => $checkIn,
        'booking.check_out' => $checkOut,
        'booking.total_price' => $totalPrice,
        'booking.duration' => $duration,
    ]);

    $fasilitas = DB::select('CALL get_propertyFacilitiesByProperty(?)', [$propertyId]);
    $images = DB::select('CALL get_propertyImagesByProperty(?)', [$propertyId]);

    return view('customers.pemesanan', [
        'selectedRooms' => $selectedRooms,
        'property' => $property[0] ?? null,
        'fasilitas' => $fasilitas,
        'images' => $images,
        'totalPrice' => $totalPrice,
        'checkIn' => $checkIn,
        'checkOut' => $checkOut
    ]);
}

    public function profile()
    {
        // Ambil data pengguna dari database
        $userId = Auth::id();
        $user = DB::select("CALL get_user_profile(?)", [$userId]);

        if (!$user || empty($user)) {
            abort(404);
        }

        $user = (object)$user[0]; // Konversi ke objek
        return view('customers.profile', compact('user'));
    }

    public function tentang()
    {
        return view('customers.tentang'); // Pastikan file 'tentang.blade.php' ada di resources/views/
    }


public function store_bokings(Request $request)
{
    if (! Auth::check()) {
        return back()->withErrors(['error' => 'Silakan login terlebih dahulu']);
    }

    // Ambil data booking awal dari session
    $selectedRooms = session('booking.selected_rooms');
    $propertyId    = session('booking.property_id');
    $checkIn       = session('booking.check_in');
    $checkOut      = session('booking.check_out');
    $totalPrice    = session('booking.total_price');
    $userDetails   = session('booking.user_details', []);
    $userId        = Auth::id();

    if (! $selectedRooms || ! $propertyId || ! $checkIn || ! $checkOut || empty($userDetails)) {
        return back()->with('error', 'Data pemesanan tidak lengkap atau sudah expired. Ulangi proses.');
    }

    // Siapkan payload untuk SP create_Booking
    $payload = [
        'user_id'     => $userId,
        'property_id' => $propertyId,
        'check_in'    => $checkIn,
        'check_out'   => $checkOut,
        'total_price' => $totalPrice,
        'guest_name'  => $userDetails['guest_name'],
        'email'       => $userDetails['email'],
        'nik'         => $userDetails['nik'],
        'rooms'       => $selectedRooms,
    ];

    // Panggil SP untuk membuat booking
    $result = DB::select('CALL create_Booking(?)', [json_encode($payload)]);
    $bookingId = $result[0]->booking_id ?? null;

    if (! $bookingId) {
        return back()->withErrors(['error' => 'Gagal menyimpan pemesanan.']);
    }

    // *** Setelah booking berhasil dibuat, langsung generate Snap Token ***
    try {
        Config::$serverKey    = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $transactionDetails = [
            'order_id'     => 'BOOKING-' . $bookingId,
            'gross_amount' => (int)$totalPrice,
        ];
        $customerDetails = [
            'first_name' => $userDetails['guest_name'],
            'email'      => $userDetails['email'],
        ];
        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details'    => $customerDetails,
            'enabled_payments'    => ['gopay', 'bank_transfer'],
        ];

        $snapToken = Snap::getSnapToken($params);

        // Simpan snapToken ke DB (opsional, sesuai SP yang Anda miliki)
        DB::statement('CALL update_snap_token(?, ?)', [$bookingId, $snapToken]);
    } catch (\Exception $e) {
        Log::error('Midtrans Error: ' . $e->getMessage());
        // Kalau gagal bikin Snap Token, redirect ke halaman error
        return back()->withErrors(['error' => 'Gagal membuat Snap Token: ' . $e->getMessage()]);
    }

    // Hapus session booking supaya tidak double submit
    session()->forget([
        'booking.selected_rooms',
        'booking.property_id',
        'booking.check_in',
        'booking.check_out',
        'booking.total_price',
        'booking.duration',
        'booking.user_details',
    ]);

    // Redirect ke halaman pembayaran dengan menambahkan snap_token sebagai query param
    return redirect()->route('payment.show', [
        'booking_id' => $bookingId,
        'snap_token' => $snapToken,
    ]);
}


public function confirm_booking(Request $request)
{
    if (! Auth::check()) {
        return back()->withErrors(['error' => 'Silakan login terlebih dahulu']);
    }

    // Ambil data booking awal dari session
    $selectedRooms = session('booking.selected_rooms');
    $propertyId    = session('booking.property_id');
    $checkIn       = session('booking.check_in');
    $checkOut      = session('booking.check_out');
    $totalPrice    = session('booking.total_price');

    if (! $selectedRooms || ! $propertyId || ! $checkIn || ! $checkOut) {
        return back()->with('error', 'Data pemesanan tidak lengkap atau sudah expired. Ulangi proses.');
    }

    // VALIDASI input Isi Data Diri
    $validator = Validator::make($request->all(), [
        'name'         => 'required|string|max:255',
        'nik'          => 'required|digits:16',
        'email'        => 'required|email|max:255',
        'guest_option' => 'required|in:saya,lain',
        'nama_tamu'    => $request->input('guest_option') === 'lain'
                            ? 'required|string|max:255'
                            : 'nullable',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Tentukan nama tamu
    $guestName = $request->input('guest_option') === 'saya'
                 ? $request->input('name')
                 : $request->input('nama_tamu');

    // Simpan detail user ke session
    session([
        'booking.user_details' => [
            'name'         => $request->input('name'),
            'nik'          => $request->input('nik'),
            'email'        => $request->input('email'),
            'guest_option' => $request->input('guest_option'),
            'nama_tamu'    => $request->input('nama_tamu'),
            'guest_name'   => $guestName,
        ]
    ]);

    // Setelah validasi dan simpan session, redirect ke halaman konfirmasi (GET)
    return redirect()->route('booking.confirm.show');
}

public function show_confirm()
{
    if (! Auth::check()) {
        return redirect()->route('login')->withErrors(['error' => 'Silakan login terlebih dahulu']);
    }

    // Ambil data booking awal dari session
    $selectedRooms = session('booking.selected_rooms');
    $propertyId    = session('booking.property_id');
    $checkIn       = session('booking.check_in');
    $checkOut      = session('booking.check_out');
    $totalPrice    = session('booking.total_price');
    $userDetails   = session('booking.user_details', []);

    if (! $selectedRooms || ! $propertyId || ! $checkIn || ! $checkOut || empty($userDetails)) {
        // Belum punya data lengkap, arahkan user kembali ke halaman Isi Data Diri atau halaman awal
        return redirect()->route('some.booking.page')->with('error', 'Data pemesanan belum lengkap. Isi kembali formulir.');
    }

    // 1) Ambil detail property menggunakan SP
    $propertyResult = DB::select('CALL view_propertyById(?)', [$propertyId]);
    if (! $propertyResult || count($propertyResult) === 0) {
        return back()->with('error', 'Property tidak ditemukan.');
    }
    $property = $propertyResult[0];

    // 2) Ambil daftar gambar properti lewat SP
    $images = DB::select('CALL get_Property_Images(?)', [$propertyId]);

    // Render view konfirmasi
    return view('customers.konfirmasi-pemesanan', [
        'currentStep'   => 2,
        'property'      => $property,
        'images'        => $images,
        'selectedRooms' => $selectedRooms,
        'checkIn'       => $checkIn,
        'checkOut'      => $checkOut,
        'totalPrice'    => $totalPrice,
        'userDetails'   => $userDetails,
    ]);
}


    // public function payment_show($booking_id)
    // {
    //     // Panggil stored procedure
    //     $bookingDetails = DB::select('CALL get_BookingDetails(?)', [$booking_id]);
    
    //     // Pastikan data ditemukan
    //     if (empty($bookingDetails)) {
    //         abort(404);
    //     }
    
    //     // Ambil data booking utama (dari item pertama)
    //     $booking = $bookingDetails[0]; // Sekarang $booking adalah objek
    
    //     // Ambil semua detail kamar (seluruh array)
    //     $rooms = $bookingDetails;
    
    //     // Kirim data ke view
    //     return view('customers.pembayaran', compact('booking', 'rooms'));
    // }
    
    public function riwayat_transaksi(Request $request)
{
    // 1. Pastikan user sudah login
    $userId = auth()->id();
    if (! $userId) {
        return redirect()->route('login');
    }

    // 2. Ambil query parameter 'status' (jika ada), misal ?status=Belum Dibayar
    $statusFilter = $request->query('status');

    // 3. Hitung tanggal hari ini dalam format YYYY-MM-DD
    $today = Carbon::now()->toDateString();

    // 4. Cari booking yang sudah lewat check_out untuk user ini
    //    a) status = 'Belum Dibayar' & check_out < hari ini -> ubah ke 'Kadaluarsa'
    //    b) status = 'Berhasil'     & check_out < hari ini -> ubah ke 'Selesai'
    try {
        // 4a. Cari semua ID booking agar status perlu di‐set ke 'Kadaluarsa'
        $toExpire = DB::table('bookings')
            ->where('user_id', $userId)
            ->whereDate('check_out', '<', $today)
            ->where('status', 'Belum Dibayar')
            ->pluck('id');

        foreach ($toExpire as $bookingId) {
            // Panggil SP sehingga hanya status yang berubah, stok tidak terpengaruh
            DB::statement('CALL update_booking_status1(?, ?)', [
                $bookingId,
                'Kadaluarsa'
            ]);
        }

        // 4b. Cari semua ID booking agar status perlu di‐set ke 'Selesai'
        $toFinish = DB::table('bookings')
            ->where('user_id', $userId)
            ->whereDate('check_out', '<', $today)
            ->where('status', 'Berhasil')
            ->pluck('id');

        foreach ($toFinish as $bookingId) {
            // Panggil SP sehingga stok otomatis di‐increment,
            // lalu status diubah menjadi 'Selesai'
            DB::statement('CALL update_booking_status(?, ?)', [
                $bookingId,
                'Selesai'
            ]);
        }
    } catch (\Exception $e) {
        Log::error("Gagal meng‐update status otomatis (Kadaluarsa/Selesai) untuk user #{$userId}: " . $e->getMessage());
        // Jika gagal, lanjutkan saja agar user tetap bisa melihat riwayat
    }

    // 5. Panggil Stored Procedure untuk mengambil semua booking + detail kamar
    //    SP get_BookingsByUserIdtest(user_id, status)
    $results = DB::select('CALL get_BookingsByUserIdtest(?, ?)', [$userId, $statusFilter]);

    // 6. Susun hasil SP menjadi koleksi per booking_id
    $bookings = collect($results)
        ->groupBy('booking_id')
        ->map(function ($group) {
            $booking = $group->first();

            // Ambil semua detail kamar untuk booking ini
            $details = $group->map(function ($item) {
                return [
                    'room_type'      => $item->room_type,
                    'quantity'       => $item->quantity,
                    'price_per_room' => $item->price_per_room,
                    'subtotal'       => $item->subtotal,
                ];
            });

            return (object)[
                'booking_id'          => $booking->booking_id,
                'property_name'       => $booking->property_name,
                'property_address'    => $booking->property_address,
                'alamat_selengkapnya' => $booking->alamat_selengkapnya,
                'check_in'            => $booking->check_in,
                'check_out'           => $booking->check_out,
                'total_price'         => $booking->total_price,
                'status'              => $booking->status,
                'guest_name'          => $booking->guest_name,
                'email'               => $booking->email,
                'reviewed'            => $booking->reviewed,
                'property_image'      => $booking->property_image,
                'rooms'               => $details,
            ];
        })
        ->values();

    // 7. Kirim data ke view 'customers.riwayat-transaksi'
    return view('customers.riwayat-transaksi', compact('bookings'));
}



    public function search_welcomeProperty(Request $request)
    {
        $keyword = $request->input('keyword');

        $results = DB::select('CALL SearchPropertiesByKeyword(?)', [$keyword]);
        $properties = collect($results);

        // Tambahkan rating ke tiap property
        foreach ($properties as &$property) {
            $ratingData = DB::select("CALL get_AverageRating(?)", [$property->property_id]);
            $property->avg_rating = $ratingData[0]->avg_rating ?? 0;
            $property->total_reviews = $ratingData[0]->total_reviews ?? 0;
        }
        unset($property);

        // Pagination manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $currentItems = $properties->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $totalItems = $properties->count();

        $paginatedProperties = new LengthAwarePaginator(
            $currentItems,
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('customers.hasil-searchWelcome', [
            'paginatedProperties' => $paginatedProperties,
            'keyword' => $keyword
        ]);
    }

    public function detail_transaksi($booking_id)
    {
        $userId = auth()->id();

        // Panggil SP untuk ambil 1 booking + semua detail kamarnya
        $results = DB::select('CALL get_BookingsByUserIdtest(?, ?)', [$userId, null]);

        // Filter hanya booking yang diminta
        $filtered = collect($results)->where('booking_id', $booking_id);

        if ($filtered->isEmpty()) {
            abort(404, 'Transaksi tidak ditemukan.');
        }

        // Ambil booking utama
        $booking = $filtered->first();

        // Ambil daftar kamarnya
        $rooms = $filtered->map(function ($item) {
            return [
                'room_type' => $item->room_type,
                'quantity' => $item->quantity,
                'price_per_room' => $item->price_per_room,
                'subtotal' => $item->subtotal,
            ];
        });

        return view('customers.detail-transaksi', compact('booking', 'rooms'));
    }


}