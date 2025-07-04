<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OwnerController extends Controller
{

    public function showOwnerpage()
    {
        $userId = auth()->id();

        try {
            // 1. Ambil properti milik owner via Stored Procedure view_propertiesByidowner
            $properties = DB::select('CALL view_propertiesByidowner(?)', [$userId]);
            $propertyCount = count($properties);

            // 2. Kumpulkan ID semua properti milik owner
            $propertyIds = collect($properties)->pluck('id')->toArray();

            // 3. Jika owner tidak punya properti, set default ke 0 / array kosong
            if (empty($propertyIds)) {
                $bookingCount          = 0;
                $pendingApprovalCount  = 0;
                $monthlySalesLabels    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                $monthlySalesData      = array_fill(0, count($monthlySalesLabels), 0);
                $recentBookings        = collect([]);
                $revenueThisMonth      = 0;
                $totalRevenue          = 0;
            } else {
                // 4. Hitung Total Bookings
                $bookingCount = DB::table('bookings')
                    ->whereIn('property_id', $propertyIds)
                    ->count();

                // 5. Hitung Pending Approvals (status 'Belum Dibayar')
                $pendingApprovalCount = DB::table('bookings')
                    ->whereIn('property_id', $propertyIds)
                    ->where('status', 'Belum Dibayar')
                    ->count();

                /**
                 * 6. Panggil Stored Procedure get_owner_revenue
                 *    Mengembalikan satu record dengan kolom:
                 *      - revenue_this_month
                 *      - total_revenue
                 */
                $revenueResult = DB::select('CALL get_owner_revenue(?)', [$userId]);

                // SP mengembalikan array dengan 1 elemen stdClass
                if (isset($revenueResult[0])) {
                    $revenueThisMonth = $revenueResult[0]->revenue_this_month;
                    $totalRevenue     = $revenueResult[0]->total_revenue;
                } else {
                    $revenueThisMonth = 0;
                    $totalRevenue     = 0;
                }

                // 7. Data grafik bulanan (status 'confirmed', tahun sekarang)
                $currentYear = Carbon::now()->year;
                $monthlySalesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                $monthlySalesData = [];
                foreach ($monthlySalesLabels as $index => $month) {
                    $monthNumber = $index + 1;
                    $sum = DB::table('bookings')
                        ->whereIn('property_id', $propertyIds)
                        ->where('status', 'Selesai')
                        ->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $monthNumber)
                        ->sum('total_price');
                    $monthlySalesData[] = $sum;
                }

                // 8. Ambil 5 booking terbaru (Recent Bookings)
                $recentBookings = DB::table('bookings')
                    ->select(
                        'bookings.id',
                        'bookings.status',
                        'bookings.created_at',
                        'bookings.check_in',
                        'bookings.check_out',
                        'bookings.total_price',
                        'bookings.guest_name',
                        'properties.name as property_name',
                        'users.name as user_name'
                    )
                    ->join('properties', 'bookings.property_id', '=', 'properties.id')
                    ->join('users', 'bookings.user_id', '=', 'users.id')
                    ->whereIn('bookings.property_id', $propertyIds)
                    ->orderBy('bookings.created_at', 'desc')
                    ->limit(5)
                    ->get();
            }

            // 9. (Opsional) Ambil total user global
            $userCount = DB::table('users')->count();

            // 10. Render view 'owner.dashboard-owner' dengan variabel‐variabel di atas
            return view('owner.dashboard-owner', compact(
                'propertyCount',
                'bookingCount',
                'pendingApprovalCount',
                'userCount',
                'revenueThisMonth',
                'totalRevenue',
                'monthlySalesLabels',
                'monthlySalesData',
                'recentBookings'
            ));
        } catch (\Exception $e) {
            // Jika ada error (SP gagal, koneksi, dsb), tampilkan pesan error
            return back()->withErrors(['error' => 'Gagal mengambil data dashboard: ' . $e->getMessage()]);
        }
    }

    public function showPropertypage(Request $request)
    {
        $userId = auth()->id();

        // 1. Ambil data via SP
        $props = DB::select('CALL view_propertiesByidowner(?)', [$userId]);

        // 2. Filter search jika ada
        if ($request->filled('search')) {
            $keyword = Str::lower($request->search);
            $props = array_filter(
                $props,
                fn($p) =>
                Str::contains(Str::lower($p->name), $keyword) ||
                    Str::contains(Str::lower($p->property_type), $keyword) ||
                    Str::contains(Str::lower($p->subdistrict), $keyword)
            );
        }

        // 3. Konversi ke koleksi dan sort descending by created_at
        $collection = collect($props)
            ->sortByDesc(fn($p) => strtotime($p->created_at));

        // 4. Pagination manual
        $perPage      = 10;
        $currentPage  = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $collection
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(
            $currentItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]
        );

        // 5. Kirim ke view
        return view('owner.property', [
            'properties' => $paginator
        ]);
    }

    public function add_property()
    {
        $propertyTypes = DB::select("CALL get_PropertyTypes()");
        $facilities = DB::select("CALL get_Facilities()");

        return view('owner.add-property', compact('propertyTypes', 'facilities'));
    }

    public function store_property(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:properties,name',
            'property_type_id'  => 'required|integer|exists:property_types,id',
            'subdis_id'         => 'required|integer|exists:subdistricts,id',
            'description'       => 'required|string',
            'full_address'      => 'required|string',        // ← tambahkan validasi
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'facilities'        => 'nullable|array',
            'facilities.*'      => 'integer|exists:facilities,id',
            'images'            => 'nullable|array',
            'images.*'          => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userId = auth()->id();
        DB::beginTransaction();

        try {
            // Siapkan payload JSON termasuk full_address
            $dataProperty = json_encode([
                'name'              => $request->name,
                'property_type_id'  => (int) $request->property_type_id,  // Pastikan ini adalah integer
                'subdis_id'         => (int) $request->subdis_id,         // Pastikan ini adalah integer
                'description'       => $request->description,
                'full_address'      => $request->full_address,
                'user_id'           => $userId,
                'latitude'          => $request->latitude,
                'longitude'         => $request->longitude,
            ]);

            // Panggil stored procedure
            DB::statement('CALL store_property(?)', [$dataProperty]);

            // Ambil ID baru
            $newPropertyId = DB::select('SELECT LAST_INSERT_ID() as id')[0]->id;

            // Simpan fasilitas
            if ($request->filled('facilities')) {
                foreach ($request->facilities as $facilityId) {
                    $payload = json_encode([
                        'property_id' => $newPropertyId,
                        'facility_id' => $facilityId,
                    ]);
                    DB::statement('CALL store_facilitiesProperty(?)', [$payload]);
                }
            }

            // Simpan gambar
            if ($request->hasFile('images')) {
                $destinationPath = public_path('storage/property_images');

                // Pastikan foldernya ada
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                foreach ($request->file('images') as $image) {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                    // Pindahkan gambar ke folder yang ditentukan
                    $image->move($destinationPath, $filename);

                    // Path relatif untuk disimpan di database
                    $path = 'property_images/' . $filename;

                    // Simpan path gambar di database menggunakan stored procedure
                    $payload = json_encode([
                        'property_id' => $newPropertyId,
                        'images_path' => $path,
                    ]);
                    DB::statement('CALL store_photoProperty(?)', [$payload]);
                }
            }

            DB::commit();

            return redirect()->route('owner.property')
                ->with('success', 'Properti berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();

            // Log error for debugging purposes
            \Log::error('Error storing property: ' . $e->getMessage());

            // Return error message to the user
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan properti: ' . $e->getMessage());
        }
    }

    public function delete_property(Request $request)
    {
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
        // Ambil properti utama
        $rawProp = DB::select("CALL view_propertyById(?)", [$propertyId]);
        if (!$rawProp) {
            return redirect()->route('owner.property')
                ->with('error', 'Properti tidak ditemukan.');
        }
        $property = $rawProp[0];

        // Prefill lokasi
        $loc = DB::select("CALL view_SubdistrictWithParents(?)", [$property->subdis_id])[0];

        // Ambil data lokasi dropdown
        $provinces = DB::select("CALL get_Provinces()");
        $regencies = DB::select("CALL get_CitiesByProvince(?)", [$loc->province_id]);
        $districts = DB::select("CALL get_DistrictsByCity(?)", [$loc->city_id]);
        $subdistricts = DB::select("CALL get_SubdistrictsByDistrict(?)", [$loc->dist_id]);

        // Ambil gambar, fasilitas, tipe properti, semua fasilitas, kamar
        $images = DB::select("CALL get_propertyImagesByProperty(?)", [$propertyId]);
        $facilities = DB::select("CALL get_propertyFacilitiesByProperty(?)", [$propertyId]); // fasilitas terpilih
        $propertyTypes = DB::select("CALL get_PropertyTypes()");
        $allFacilities = DB::select("CALL get_facilities()"); // semua fasilitas

        $rooms = DB::select("CALL get_RoomsByPropertyId(?)", [$propertyId]);
        $rooms = collect($rooms)->sortByDesc('created_at')->values()->all();

        return view('owner.kelola-property', [
            'property'      => $property,
            'loc'           => $loc,
            'provinces'     => $provinces,
            'cities'        => $regencies,
            'districts'     => $districts,
            'subdistricts'  => $subdistricts,
            'images'        => $images,
            'facilities'    => $facilities,
            'propertyTypes' => $propertyTypes,
            'rooms'         => $rooms,
            'allFacilities' => $allFacilities,
        ]);
    }


    public function update_property(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'property_name'       => 'required|string|max:255',
            'property_type'       => 'required|integer',
            'subdis_id'           => 'required|integer',
            'alamat_selengkapnya' => 'required|string|max:500',
            'description'         => 'required|string',
            'latitude'            => 'required|numeric',
            'longitude'           => 'required|numeric',
        ]);

        // 2. Siapkan JSON payload sesuai yang diekstrak SP
        $data = [
            'property_id'         => $id,
            'name'                => $request->property_name,
            'property_type_id'    => $request->property_type,
            'subdis_id'           => $request->subdis_id,
            'alamat_selengkapnya' => $request->alamat_selengkapnya,
            'description'         => $request->description,
            'latitude'            => $request->latitude,
            'longitude'           => $request->longitude,
        ];

        // 3. Panggil Stored Procedure
        try {
            DB::statement('CALL update_property(?)', [json_encode($data)]);
            return redirect()
                ->route('owner.property')
                ->with('success', 'Properti berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui properti: ' . $e->getMessage());
        }
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
            'facility_id' => [
                'required',
                Rule::exists('property_facilities', 'id')->where('is_deleted', 0),
            ],
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
                "name" => $facility->name,
                "icon" => $facility->icon
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
                // Generate unique filename using time() and uniqid()
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Define the destination path for storing images
                $destinationPath = public_path('storage/property_images');

                // Ensure the folder exists, if not create it
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Move the uploaded image to the designated folder
                $image->move($destinationPath, $filename);

                // Store the relative path in the database
                $path = 'property_images/' . $filename;

                // Call stored procedure to save the image data
                DB::statement("CALL Add_PropertyImage(?, ?)", [$request->property_id, $path]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Gambar berhasil diunggah terbaru.']);
    }


    public function addRoom(Request $request)
    {
        $request->validate([
            'property_id'   => [
                'required',
                'integer',
                Rule::exists('properties', 'id')
            ],
            'room_type'     => 'required|string|max:100',
            'price'         => 'required|numeric|min:0|max:999999999',
            'stok'          => 'required|integer|min:1',
            'room_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, &$newRoom) {
                $roomData = [
                    'property_id'    => $request->property_id,
                    'room_type'      => $request->room_type,
                    'total_room'     => $request->stok,
                    'available_room' => $request->stok,
                ];
                $roomResult = DB::select('CALL store_room(?)', [json_encode($roomData)]);
                $roomId     = $roomResult[0]->room_id;

                DB::statement('CALL store_roomPrice(?)', [json_encode([
                    'room_id' => $roomId,
                    'price'   => $request->price,
                ])]);

                // Menyimpan gambar kamar
                if ($request->hasFile('room_images')) {
                    $destinationPath = public_path('storage/room_images');

                    // Pastikan foldernya ada
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    foreach ($request->file('room_images') as $img) {
                        // Buat nama file unik
                        $filename = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();

                        // Pindahkan gambar ke folder yang ditentukan
                        $img->move($destinationPath, $filename);

                        // Simpan path relatif untuk digunakan di database
                        $path = 'room_images/' . $filename;

                        // Panggil stored procedure untuk menyimpan gambar
                        DB::statement('CALL store_roomImage(?)', [json_encode([
                            'room_id'    => $roomId,
                            'image_path' => $path,
                        ])]);
                    }
                }
            });

            // redirect dengan flash message
            return redirect()
                ->back()
                ->with('success', 'Kamar berhasil ditambahkan.');
        } catch (\Exception $e) {
            // redirect dengan error message
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan kamar: ' . $e->getMessage());
        }
    }

    // Tambahkan di OwnerController.php
    public function editRoom($id)
    {
        // Misal: ambil data room dari DB
        $room = DB::table('rooms')->where('room_id', $id)->first();

        if (!$room) {
            abort(404);
        }

        return view('owner.edit-room', compact('room'));
    }

    public function update_room(Request $request, $propertyId, $roomId)
    {
        $request->validate([
            'room_type'  => 'required|string|max:255',
            'price'      => 'required|numeric',
            'stok'       => 'required|integer',
            'room_image' => 'nullable|image|max:2048',
        ]);

        // Default: path gambar (jika tidak update, tetap null/empty)
        $imagePath = null;

        if ($request->hasFile('room_image')) {
            $file = $request->file('room_image');
            $filename = time() . '_' . preg_replace('/\s+/', '', $request->room_type) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/room_images'), $filename);
            $imagePath = 'room_images/' . $filename;
        }

        // CALL SP (pastikan sesuai urutan!)
        DB::statement('CALL sp_update_room(?, ?, ?, ?, ?)', [
            $roomId,
            $request->room_type,
            $request->price,
            $request->stok,
            $imagePath
        ]);

        return back()->with('success', 'Kamar berhasil diupdate!');
    }




    public function deleteRoom($room_id)
    {
        try {
            DB::transaction(function () use ($room_id) {
                // Panggil SP soft_delete_room
                $payload = json_encode(['room_id' => $room_id]);
                DB::statement('CALL soft_delete_room(?)', [$payload]);
            });

            return redirect()
                ->back()
                ->with('success', 'Kamar berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus kamar: ' . $e->getMessage());
        }
    }

    public function get_FacilitiesByPropertyId($propertyId)
    {
        try {
            // Panggil SP dengan parameter property_id
            $facilities = DB::select('CALL get_propertyFacilitiesByProperty(?)', [$propertyId]);

            // Kembalikan data dalam format JSON
            return response()->json($facilities);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function riwayat_transaksi(Request $request)
    {
        $user = auth()->user();

        // Pastikan user adalah pemilik properti
        if (!$user || $user->user_role_id != 2) {
            abort(403, 'Akses ditolak. Anda bukan pemilik properti.');
        }

        try {
            // Panggil stored procedure
            $raw = DB::select('CALL GetBookingsByOwnerId(?)', [$user->id]);

            // Konversi ke Collection
            $collection = collect($raw);

            // Setup pagination
            $perPage = 10;
            $page    = $request->get('page', 1);
            $slice   = $collection->slice(($page - 1) * $perPage, $perPage)->values();

            $bookings = new LengthAwarePaginator(
                $slice,
                $collection->count(),
                $perPage,
                $page,
                [
                    'path'  => $request->url(),
                    'query' => $request->query(),
                ]
            );

            // Kirim ke view
            return view('owner.booking', compact('bookings'));
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal mengambil data booking: ' . $e->getMessage()
            ]);
        }
    }

    public function detail_booking_owner($booking_id)
    {
        $ownerId = auth()->id();

        // Panggil stored procedure
        $results = DB::select('CALL get_BookingsByOwnerId3(?, ?)', [
            $ownerId,
            $booking_id
        ]);

        if (empty($results)) {
            abort(404, 'Transaksi tidak ditemukan.');
        }

        // Ambil data booking utama dari row pertama
        $first = $results[0];
        $booking = (object) [
            'id'               => $first->booking_id,
            'check_in'         => $first->check_in,
            'check_out'        => $first->check_out,
            'total_price'      => $first->total_price,
            'guest_name'       => $first->guest_name,
            'email'            => $first->email,
            'status'           => $first->status,
            'nik'              => $first->nik,
            'reviewed'         => $first->reviewed,
            'property_name'    => $first->property_name,
            'property_address' => $first->property_address,
            'property_image'   => $first->property_image,
        ];

        // Kumpulkan detail kamar
        $rooms = collect($results)->map(function ($item) {
            return (object) [
                'room_type'      => $item->room_type,
                'quantity'       => $item->quantity,
                'price_per_room' => $item->price_per_room,
                'subtotal'       => $item->subtotal,
            ];
        });

        return view('owner.detail-transaksi', compact('booking', 'rooms'));
    }

    public function show()
    {
        $userId = Auth::id();

        // Ambil data join users dan pemilik_properti (LEFT JOIN agar tetap tampil meski data belum lengkap)
        $data = DB::table('users')
            ->leftJoin('pemilik_properti', 'users.id', '=', 'pemilik_properti.id_users')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'users.email_verified_at',
                'pemilik_properti.address_pemilik_properti',
                'pemilik_properti.phone_pemilik_properti',
                'pemilik_properti.gender_pemilik_properti',
                'pemilik_properti.ktp',
                'pemilik_properti.photo'
            )
            ->where('users.id', $userId)
            ->first();

        return view('owner.profile', compact('data'));
    }

    public function edit()
    {
        $userId = Auth::id();
        $data = DB::table('users')
            ->leftJoin('pemilik_properti', 'users.id', '=', 'pemilik_properti.id_users')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'pemilik_properti.address_pemilik_properti',
                'pemilik_properti.phone_pemilik_properti',
                'pemilik_properti.gender_pemilik_properti',
                'pemilik_properti.ktp',
                'pemilik_properti.photo'
            )
            ->where('users.id', $userId)
            ->first();
        return view('owner.edit-profile', compact('data'));
    }

    public function update(Request $request)
    {
        $userId = Auth::id();

        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'address_pemilik_properti' => 'nullable|string|max:255',
            'phone_pemilik_properti' => 'nullable|string|max:20',
            'gender_pemilik_properti' => 'nullable|string|max:10',
            'ktp' => 'nullable|image|max:2048',    // Foto KTP
            'photo' => 'nullable|image|max:2048',  // Foto profil
        ]);

        $phone = $request->phone_pemilik_properti ?: null;

        // Update tabel users
        DB::table('users')->where('id', $userId)->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        // Ambil data lama
        $oldData = DB::table('pemilik_properti')->where('id_users', $userId)->first();

        // Handle upload foto profil (profile_owner)
        $photoPath = $oldData->photo ?? null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            // Hapus lama
            if ($photoPath && file_exists(public_path('storage/' . $photoPath))) {
                @unlink(public_path('storage/' . $photoPath));
            }
            $filename = time() . '_' . preg_replace('/\s+/', '', $request->username) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('storage/profile_owner/');
            if (!file_exists($dest)) {
                mkdir($dest, 0777, true);
            }
            $file->move($dest, $filename);
            $photoPath = 'profile_owner/' . $filename;
        }

        // Handle upload foto KTP (ktp_owner)
        $ktpPath = $oldData->ktp ?? null;
        if ($request->hasFile('ktp')) {
            $file = $request->file('ktp');
            if ($ktpPath && file_exists(public_path('storage/' . $ktpPath))) {
                @unlink(public_path('storage/' . $ktpPath));
            }
            $filename = time() . '_' . preg_replace('/\s+/', '', $request->username) . '.' . $file->getClientOriginalExtension();
            $dest = public_path('storage/ktp_owner/');
            if (!file_exists($dest)) {
                mkdir($dest, 0777, true);
            }
            $file->move($dest, $filename);
            $ktpPath = 'ktp_owner/' . $filename;
        }

        try {
            DB::statement('CALL upsert_pemilik_properti(?, ?, ?, ?, ?, ?, ?)', [
                $userId,                             // id_users
                $request->address_pemilik_properti,  // address_pemilik_properti
                $phone,                              // phone_pemilik_properti
                $request->gender_pemilik_properti,   // gender_pemilik_properti
                $ktpPath,                            // path foto KTP
                $photoPath,                          // path foto profil
                now()                                // updated_at
            ]);
            return redirect()->route('profileowner.show')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('profileowner.show')
                ->with('error', 'Gagal memperbarui profil! ' . $e->getMessage());
        }
    }
}
