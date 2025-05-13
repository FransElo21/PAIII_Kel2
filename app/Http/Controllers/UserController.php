<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $type = 1;  // Menetapkan type = 1 (Homestay)

        // Mengambil hasil pencarian properti dengan type 1 (Homestay)
        $properties = DB::select("CALL sp_search_property(?, ?, ?, ?, ?, ?)", [
            $keyword,
            $cityId,
            $priceMin,
            $priceMax,
            $orderBy,
            $type  // Menambahkan parameter type untuk membatasi pencarian ke Homestay
        ]);

        // Mengambil daftar kota
        $cities = DB::select('CALL sp_get_cities()');

        // Mengirimkan data ke view
        return view('customers.hasil-searchHomestay', compact('properties', 'cities'));
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
        // 1. Ambil semua parameter dari request
        $encodedRooms = $request->query('rooms');
        $propertyId = $request->query('property_id'); // Tambahkan ini untuk ambil property_id
        $userId = $request->query('user_id'); // Jika butuh user_id
        
        // 2. Validasi parameter wajib
        if (!$encodedRooms || !$propertyId) {
            return back()->with('error', 'Data pemesanan tidak lengkap.');
        }

        try {
            // 3. Decode JSON rooms dari URL
            $selectedRooms = json_decode(urldecode($encodedRooms), true);
            
            // 4. Ambil data dari database menggunakan stored procedures
            $property = DB::select('CALL view_propertyById(?)', [$propertyId]);
            $fasilitas = DB::select('CALL get_propertyFacilitiesByProperty(?)', [$propertyId]);

            $propertyImages = DB::select('CALL get_propertyImagesByProperty(?)', [$propertyId]);
            $images = $propertyImages; // Gunakan data asli tanpa pluck
            
            // 5. Ambil detail kamar dari database (opsional, jika perlu validasi)
            $rooms = DB::select("CALL get_RoomsByPropertyId(?)", [$propertyId]);

            // 6. Format data agar bisa digunakan di view
            $property = $property[0] ?? null; // Ambil objek tunggal dari array

            return view('customers.pemesanan', compact(
                'selectedRooms',
                'property',
                'fasilitas',
                'rooms',
                'images'
            ));
            
        } catch (\Exception $e) {
            // 7. Handle error database
            return back()->with('error', 'Gagal memuat data pemesanan: ' . $e->getMessage());
        }
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
        // Validasi input pengguna
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'email' => 'required|email|max:255',
            'guest_option' => 'required|in:saya,lain',
            'nama_tamu' => $request->guest_option === 'lain' ? 'required|string|max:255' : 'nullable',
            'property_id' => 'required|integer|exists:properties,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'total_price' => 'required|numeric|min:0',
            'rooms' => 'required|json'
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        try {
            // Pastikan pengguna login
            if (!Auth::check()) {
                return back()->withErrors(['error' => 'Silakan login terlebih dahulu']);
            }
    
            $data = $request->all();
            $userId = Auth::id();
    
            // Decode data kamar
            $rooms = json_decode($data['rooms'], true);
            if (!is_array($rooms)) {
                throw new \Exception("Data kamar tidak valid");
            }
    
            // Siapkan data untuk dikirim ke SP
            $guestName = $data['guest_option'] === 'saya' 
                ? $data['name'] 
                : $data['nama_tamu'];
    
            $payload = [
                'user_id' => $userId,
                'property_id' => $data['property_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'total_price' => $data['total_price'],
                'guest_name' => $guestName,
                'email' => $data['email'],
                'nik' => $data['nik'],
                'rooms' => $rooms,
            ];
    
            // Panggil Stored Procedure (SP)
            $result = DB::select('CALL create_Booking(?)', [json_encode($payload)]);
            $bookingId = $result[0]->booking_id;
    
            // Arahkan ke halaman pembayaran
            return redirect()->route('payment.show', ['booking_id' => $bookingId]);
        } catch (\Exception $e) {
            // Tangkap error dan kembalikan ke halaman sebelumnya
            return back()->withErrors(['error' => 'Gagal menyimpan pemesanan: ' . $e->getMessage()]);
        }
    }

    public function payment_show($booking_id)
    {
        // Panggil stored procedure
        $bookingDetails = DB::select('CALL get_BookingDetails(?)', [$booking_id]);
    
        // Pastikan data ditemukan
        if (empty($bookingDetails)) {
            abort(404);
        }
    
        // Ambil data booking utama (dari item pertama)
        $booking = $bookingDetails[0]; // Sekarang $booking adalah objek
    
        // Ambil semua detail kamar (seluruh array)
        $rooms = $bookingDetails;
    
        // Kirim data ke view
        return view('customers.pembayaran', compact('booking', 'rooms'));
    }
    
    public function riwayat_transaksi(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $status = $request->query('status');

        $results = DB::select('CALL get_BookingsByUserIdtest(?, ?)', [$userId, $status]);

        // Kelompokkan booking dan detail kamar
        $bookings = collect($results)->groupBy('booking_id')->map(function ($group) {
            $booking = $group->first();

            // Ambil semua detail kamar
            $details = $group->map(function ($item) {
                return [
                    'room_type' => $item->room_type,
                    'quantity' => $item->quantity,
                    'price_per_room' => $item->price_per_room,
                    'subtotal' => $item->subtotal,
                ];
            });

            return (object)[
                'booking_id' => $booking->booking_id,
                'property_name' => $booking->property_name,
                'property_address' => $booking->property_address,
                'check_in' => $booking->check_in,
                'check_out' => $booking->check_out,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'guest_name' => $booking->guest_name,
                'email' => $booking->email,
                'reviewed' => $booking->reviewed,
                'property_image' => $booking->property_image,
                'rooms' => $details,
            ];
        })->values();

        return view('customers.riwayat-transaksi', compact('bookings'));
    }

    public function search_welcomeProperty(Request $request)
    {
        // Ambil keyword dari input
        $keyword = $request->input('keyword');

        // Panggil SP dengan keyword
        $results = DB::select('CALL SearchPropertiesByKeyword(?)', [$keyword]);

        // Konversi ke koleksi Laravel
        $properties = collect($results);

        // Pagination manual (karena SP tidak mendukung pagination langsung)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $currentItems = $properties->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $totalItems = $properties->count();

        $paginatedProperties = new LengthAwarePaginator(
            $currentItems,
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('customers.hasil-searchWelcome', compact('paginatedProperties', 'keyword'));
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