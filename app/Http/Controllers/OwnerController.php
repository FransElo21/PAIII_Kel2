<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OwnerController extends Controller
{
    public function showOwnerpage() {
        return view('owner.dashboard-owner'); 
    }

    public function showPropertypage() {
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login

        // Panggil Stored Procedure
        $properties = DB::select('CALL view_propertiesByidowner(?)', [$userId]);
        return view('owner.property', compact('properties')); 
    }

    // public function showPropertyDetail($propertyId) {
    //     // Panggil Stored Procedure untuk mendapatkan detail properti berdasarkan ID
    //     $property = DB::select('CALL view_propertyById(?)', [$propertyId]);
    
    //     // Pastikan data yang dikembalikan tidak kosong
    //     if (empty($property)) {
    //         return redirect()->route('owner.property')->with('error', 'Properti tidak ditemukan.');
    //     }
    
    //     return view('owner.property', ['property' => $property[0]]);
    // }    

    public function add_property(){
        $propertyTypes = DB::select("CALL get_PropertyTypes()");
        $facilities = DB::select("CALL get_Facilities()");
    
        return view('owner.add-property', compact('propertyTypes', 'facilities'));
    }       

    public function store_property(Request $request)
{
    // Validasi request
    $validator = Validator::make($request->all(), [
        'name'              => 'required|string',
        'property_type_id'  => 'required|integer|exists:property_types,id',
        'subdis_id'         => 'required|integer|exists:subdistricts,id',
        'description'       => 'required|string',
        'latitude'          => 'required|numeric',
        'longitude'         => 'required|numeric',
        'facilities'        => 'nullable|array',
        'facilities.*'      => 'integer|exists:facilities,id',
        'images'            => 'nullable|array',
        'images.*'          => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Jika validasi gagal
    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $userId = auth()->id();
    DB::beginTransaction();

    try {
        // Simpan data property via stored procedure
        $dataProperty = json_encode([
            'name'              => $request->name,
            'property_type_id'  => $request->property_type_id,
            'subdis_id'         => $request->subdis_id,
            'description'       => $request->description,
            'user_id'           => $userId,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
        ]);

        DB::statement('CALL store_property(?)', [$dataProperty]);

        // Ambil ID property terakhir yang baru dibuat
        $newPropertyId = DB::select('SELECT LAST_INSERT_ID() as id')[0]->id;

        // Simpan fasilitas property
        $facilities = $request->input('facilities');
        if (!empty($facilities)) {
            foreach ($facilities as $facilityId) {
                if ($facilityId) {
                    $dataFacilitiesProperty = json_encode([
                        'property_id' => $newPropertyId,
                        'facility_id' => $facilityId,
                    ]);
                    DB::statement('CALL store_facilitiesProperty(?)', [$dataFacilitiesProperty]);
                }
            }
        }

        // Simpan gambar property
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('property_images', 'public');
                $dataphotoProperty = json_encode([
                    'property_id' => $newPropertyId,
                    'images_path' => $path,
                ]);
                DB::statement('CALL store_photoProperty(?)', [$dataphotoProperty]);
            }
        }

        DB::commit();

        // Redirect ke halaman property dengan flash message sukses
        return redirect()->route('owner.property')->with('success', 'Properti berhasil ditambahkan.');

    } catch (\Exception $e) {
        DB::rollback();

        // Redirect balik dengan flash message error
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
    
    public function delete_property(Request $request) {
        $property_id = $request->input('property_id');
    
        // Validasi jika property_id kosong
        if (!$property_id) {
            return redirect()->back()->with('error', 'Property ID tidak ditemukan.');
        }
    
        // Panggil Stored Procedure untuk menandai properti sebagai dihapus
        DB::statement("CALL delete_property(?)", [$property_id]);
    
        return redirect()->back()->with('success', 'Property berhasil dihapus.');
    }

    public function edit_property($propertyId)
{
    // Mengambil data properti menggunakan Stored Procedure
    $propertyData = DB::select("CALL view_propertyById(?)", [$propertyId]);

    // Pastikan properti ditemukan sebelum mengambil fasilitas, gambar, dan kamar
    if (empty($propertyData)) {
        return redirect()->route('owner.property')->with('error', 'Properti tidak ditemukan.');
    }

    // Konversi hasil ke objek tunggal
    $property = $propertyData[0];

    // Mengambil gambar properti
    $images = DB::select("CALL get_propertyImagesByProperty(?)", [$propertyId]);

    // Mengambil fasilitas properti
    $facilities = DB::select("CALL get_propertyFacilitiesByProperty(?)", [$propertyId]);

    // Mengambil daftar tipe properti untuk dropdown
    $propertyTypes = DB::select("CALL get_PropertyTypes()");

    // Mengambil daftar kamar berdasarkan property_id
    $rooms = DB::select("CALL get_RoomsByPropertyId(?)", [$propertyId]);

    // Mengambil daftar subdistrict untuk dropdown
    $subdistricts = DB::select("CALL get_Subdistricts()");

    // Mengembalikan view dengan data yang diperlukan
    return view('owner.kelola-property', [
        'property' => $property,
        'images' => $images,
        'facilities' => $facilities,
        'propertyTypes' => $propertyTypes,
        'rooms' => $rooms,
        'subdistricts' => $subdistricts 
    ]);
}

public function update_property(Request $request, $id)
{
    // Validasi (opsional tapi direkomendasikan)
    $request->validate([
        'name' => 'required|string|max:255',
        'property_type' => 'required|integer',
        'subdistrict' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    // Persiapkan data JSON
    $data = [
        'property_id' => $id,
        'name' => $request->name,
        'property_type_id' => $request->property_type,
        'subdis_id' => $request->subdis_id, // <- ini penting
        'description' => $request->description,
    ];
    
    // Panggil Stored Procedure
    DB::statement('CALL update_property(?)', [json_encode($data)]);

    // Redirect dengan pesan sukses
    return redirect()->route('owner.property')->with('success', 'Properti berhasil diperbarui.');
}


public function deleteImage(Request $request)
{
    $request->validate([
        'image_id' => 'required|exists:property_images,id',
        'image_path' => 'required|string',
    ]);

    $imageId = $request->image_id;

    // Panggil Stored Procedure untuk menandai gambar sebagai dihapus
    DB::statement("CALL delete_propertyImage(?)", [$imageId]);

    return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus.']);
}

public function deleteFacility(Request $request)
{
    $request->validate([
        'facility_id' => 'required|exists:property_facilities,id',
    ]);

    $facilityId = $request->facility_id;

    DB::statement("CALL delete_propertyFacility(?)", [$facilityId]);

    return response()->json(['success' => true, 'message' => 'Fasilitas berhasil dihapus.']);
}



public function addFacility(Request $request)
{
    try {
        $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'facilities' => 'required|array',
            'facilities.*' => 'string|max:255'
        ]);

        $propertyId = $request->property_id;
        $facilities = array_unique($request->facilities); // Hapus duplikat dari input

        // Ambil fasilitas yang sudah ada di database
        $existingFacilities = DB::table('property_facilities')
            ->where('property_id', $propertyId)
            ->pluck('facility')
            ->toArray();

        // Filter hanya fasilitas yang belum ada di database
        $newFacilities = array_diff($facilities, $existingFacilities);

        if (empty($newFacilities)) {
            return response()->json(['success' => false, 'message' => 'Semua fasilitas sudah terdaftar.'], 400);
        }

        // Panggil SP hanya untuk fasilitas baru
        foreach ($newFacilities as $facility) {
            DB::statement('CALL Add_PropertyFacility(?, ?)', [$propertyId, $facility]);
        }

        return response()->json(['success' => true, 'message' => 'Fasilitas berhasil ditambahkan.']);
    } catch (\Exception $e) {
        \Log::error('Error saat menambahkan fasilitas: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}

public function getFacilities()
{
    // Panggil stored procedure
    $facilities = DB::select("CALL get_facilities()");

    $response = [];
    foreach ($facilities as $facility) {
        $response[] = [
            "id"   => $facility->id,
            "name" => $facility->name,   // Kolom dari SP (bukan facility_name)
            "icon" => $facility->icon    // Tambahkan kolom icon
        ];
    }

    return response()->json($response);
}


public function addImage(Request $request)
{
    $request->validate([
        'property_id' => 'required|integer',
        'images' => 'required|array',
        'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('property_images', 'public');
            DB::statement("CALL Add_PropertyImage(?, ?)", [$request->property_id, $path]);
        }
    }

    return response()->json(['success' => true, 'message' => 'Gambar berhasil diunggah.']);
}

public function addRoom(Request $request)
{
    try {
        // Validasi dulu
        $request->validate([
            'property_id'  => 'required|integer',
            'room_type'    => 'required|string',
            'price'        => 'required|numeric',
            'quantity'     => 'required|integer',
            'room_images'  => 'nullable|array',
            'room_images.*'=> 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Insert ke rooms table
        $roomData = [
            'property_id' => $request->property_id,
            'room_type'   => $request->room_type,
            'quantity'    => $request->quantity
        ];

        $roomResult = DB::select("CALL store_room(?)", [json_encode($roomData)]);
        $roomId = $roomResult[0]->room_id;

        // Insert ke room_prices table
        $priceData = [
            'room_id' => $roomId,
            'price'   => $request->price
        ];

        DB::statement("CALL store_roomPrice(?)", [json_encode($priceData)]);

        // Upload & insert gambar ke Storage
        if ($request->hasFile('room_images')) {
            foreach ($request->file('room_images') as $image) {
                // simpan ke storage/app/public/room_images
                $path = $image->store('room_images', 'public');

                // data JSON untuk simpan ke DB lewat SP
                $imageData = [
                    'room_id'    => $roomId,
                    'image_path' => $path // hasilnya 'room_images/namafile.jpg'
                ];

                DB::statement("CALL store_roomImage(?)", [json_encode($imageData)]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Kamar berhasil ditambahkan',
            'room_id' => $roomId
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}
}