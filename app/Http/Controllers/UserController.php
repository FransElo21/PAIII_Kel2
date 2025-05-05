<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
    
    public function search(Request $request)
        {
            $keyword = $request->input('keyword');
            $cityId = $request->input('city_id');
            $priceMin = $request->input('price_min');
            $priceMax = $request->input('price_max');
            $orderBy = $request->input('order_by');
        
            // Mengambil hasil pencarian properti
            $properties = DB::select("CALL sp_search_property(?, ?, ?, ?, ?)", [
                $keyword,
                $cityId,
                $priceMin,
                $priceMax,
                $orderBy
            ]);
        
            // Mengambil daftar kota
            $cities = DB::select('CALL sp_get_cities()');
        
            // Mengirimkan data ke view
            return view('customers.hasil-search', compact('properties', 'cities'));
        }

        public function booking_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'renter_name' => 'required|string|max:255',
            'ktp_image' => 'required|string', // asumsikan base64 / file path
            'guest_option' => 'required|in:saya,lain',
            'guest_name' => 'nullable|string|max:255',
            'tanggal_checkin' => 'required|date',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'note' => 'nullable|string|max:200',
            'details' => 'required|array|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid booking data',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Simpan booking utama dan ambil ID-nya
            DB::statement('CALL insert_booking(?, ?, ?, ?, ?, ?, @new_booking_id)', [
                auth()->id(),
                $request->renter_name,
                $request->guest_option,
                $request->guest_name,
                $request->ktp_image,
                $request->note
            ]);

            $id_booking = DB::select("SELECT @new_booking_id AS id")[0]->id;

            $checkin = Carbon::parse($request->tanggal_checkin);
            $checkout = Carbon::parse($request->tanggal_checkout);

            foreach ($request->input('details') as $detail) {
                $detailValidator = Validator::make($detail, [
                    'room_id' => 'required|integer|exists:rooms,id',
                    'quantity' => 'required|integer|min:1'
                ]);

                if ($detailValidator->fails()) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid booking detail data',
                        'errors' => $detailValidator->errors()
                    ], 400);
                }

                // Ambil harga dan tipe_sewa dari database
                $roomPrice = DB::table('room_prices')
                    ->where('room_id', $detail['room_id'])
                    ->where('is_deleted', 0)
                    ->first();

                if (!$roomPrice) {
                    throw new \Exception("Harga kamar tidak ditemukan.");
                }

                // Hitung durasi dan subtotal
                $durasi = ($roomPrice->tipe_sewa == 'monthly')
                    ? max(1, $checkin->diffInMonths($checkout))
                    : max(1, $checkin->diffInDays($checkout));

                $subtotal = $roomPrice->harga_per_unit * $durasi * $detail['quantity'];

                // Insert detail
                DB::statement('CALL insert_booking_detail(?, ?, ?, ?, ?, ?)', [
                    $id_booking,
                    $detail['room_id'],
                    $detail['quantity'],
                    $roomPrice->harga_per_unit,
                    $roomPrice->tipe_sewa,
                    $subtotal
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'booking_id' => $id_booking
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Booking creation failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
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

}
