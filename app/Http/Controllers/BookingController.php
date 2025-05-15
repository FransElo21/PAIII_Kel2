<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function showKostBooking(Request $request)
{
    // 1. Ambil semua parameter dari request
    $encodedRooms = $request->query('rooms');
    $propertyId = $request->query('property_id');
    $userId = $request->query('user_id');

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
        $images = $propertyImages;

        // 5. Ambil detail kamar dari database (opsional, jika perlu validasi)
        $rooms = DB::select("CALL get_RoomsByPropertyId(?)", [$propertyId]);

        // 6. Format data agar bisa digunakan di view
        $property = $property[0] ?? null;

        return view('customers.pemesanan_kost', compact(
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

public function store_bokingkost(Request $request)
{
    // Validasi input pengguna
    $validator = Validator::make($request->all(), [
        'nik' => 'required|digits:16',
        'email' => 'required|email|max:255',
        'guest_option' => 'required|in:saya,lain',
        'nama_tamu' => $request->guest_option === 'lain' ? 'required|string|max:255' : 'nullable',
        'property_id' => 'required|integer|exists:properties,id',
        'start_date' => 'required|date',
        'duration' => 'required|integer|min:1', // Durasi sewa (bulan)
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

        // Hitung tanggal check-out berdasarkan start_date dan durasi
        $startDate = \Carbon\Carbon::parse($data['start_date']);
        $endDate = $startDate->addMonths($data['duration'])->format('Y-m-d');

        // Siapkan data untuk dikirim ke SP
        $guestName = $data['guest_option'] === 'saya' 
            ? $data['name'] 
            : $data['nama_tamu'];

        $payload = [
            'user_id' => $userId,
            'property_id' => $data['property_id'],
            'check_in' => $data['start_date'],
            'check_out' => $endDate, // Tanggal check-out dihitung otomatis
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

}
