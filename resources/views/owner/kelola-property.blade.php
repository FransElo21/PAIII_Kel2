@extends('layouts.owner.index-owner')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- notifikasi sukses --}}
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            showToast("{{ session('success') }}", 'success');
        });
    </script>
@endif

{{-- notifikasi error --}}
@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            showToast("{{ session('error') }}", 'danger');
        });
    </script>
@endif

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .card { border-radius: 15px; box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1); padding: 25px; transition: transform 0.3s ease; }
        .card:hover { transform: translateY(-5px); }
        .list-group-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 18px; border-radius: 8px; background-color: #ffffff; transition: background-color 0.3s ease; }
        .list-group-item:hover { background-color: #f1f3f5; }
        .image-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 20px; }
        .image-item { position: relative; width: 100%; height: 180px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease; }
        .image-item:hover { transform: scale(1.05); }
        .image-item img { width: 100%; height: 100%; object-fit: cover; }
        .delete-btn { position: absolute; top: 10px; right: 10px; background: rgba(255, 0, 0, 0.8); color: white; border: none; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.3s ease; }
        .delete-btn:hover { background: red; }
        .toast { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .swal2-popup { background: #ffffff !important; color: #000000 !important; }
        .swal2-title { color: #000000 !important; }
        .swal2-content { color: #000000 !important; }
        .swal2-confirm { background-color: #3085d6 !important; }
        .form-control{ border-radius: 20px; }

        .toast {
            min-width: 300px;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        .toast-header {
            padding: 0.5rem 1rem;
        }

        .toast-body {
            padding: 0.75rem 1rem;
        }
        /* Breadcrumb */
        .breadcrumb {
            /* padding: 0.75rem 1.25rem; */
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); */
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #94a3b8;
        }

        .breadcrumb-item a {
            color: #64748b;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #289A84;
        }

        .breadcrumb-item.active {
            color: #289A84;
            font-weight: 600;
        }
        .facilities-container {
            border: 1px solid #e9ecef;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: var(--shadow-soft);
            background: #fff;
            position: relative;
            }

            .autocomplete-dropdown {
            max-height: 200px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #ced4da;
            border-top: none;
            border-radius: 0 0 0.5rem 0.5rem;
            z-index: 1050;
            display: none;
            }

            .autocomplete-item {
            padding: 8px 12px;
            cursor: pointer;
            }

            .autocomplete-item:hover {
            background: #f8f9fa;
            }

            .facility-tag {
            border-radius: 2rem;
            padding: 0.4rem 0.8rem;
            display: inline-flex;
            align-items: center;
            margin: 0.2rem;
            font-size: 0.9rem;
            }

            .facility-tag i {
            margin-right: 0.4rem;
            }

            .remove-facility {
            margin-left: 0.5rem;
            cursor: pointer;
            font-weight: bold;
            color: #fff;
            opacity: 0.8;
            }

            .remove-facility:hover {
            color: #dc3545;
            opacity: 1;
            }
    </style>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb align-items-center">
            <li class="breadcrumb-item"><a href="/property">Property</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Property</li>
        </ol>
    </nav>

    <div class="card">
        <h3 class="text-center mb-4">Kelola Properti</h3>
        <form action="{{ route('owner.update-property', $property->property_id) }}" method="POST" enctype="multipart/form-data" id="propertyForm">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="property_name" class="form-label">Nama Properti</label>
                    <input type="text" class="form-control" id="property_name" name="property_name" value="{{ $property->property_name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="property_type" class="form-label">Kategori</label>
                    <select class="form-control" id="property_type" name="property_type" required>
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type->id }}" {{ $type->id == $property->property_type_id ? 'selected' : '' }}>{{ $type->property_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row mb-3">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Provinsi</label>
                    <select id="provinceSelect" name="province_id" class="form-control">
                    <option value="">Pilih Provinsi</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p->id }}"
                        {{ $p->id == $loc->province_id ? 'selected':'' }}>
                        {{ $p->prov_name }}
                        </option>
                    @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Kota/Kabupaten</label>
                    <select id="regencySelect" name="regency_id" class="form-control">
                    <option value="">Pilih Kota/Kab</option>
                    @foreach($cities as $r)
                        <option value="{{ $r->city_id }}"
                        {{ $r->city_id == $loc->city_id ? 'selected':'' }}>
                        {{ $r->city_name }}
                        </option>
                    @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Kecamatan</label>
                    <select id="districtSelect" name="district_id" class="form-control">
                    <option value="">Pilih Kecamatan</option>
                    @foreach($districts as $d)
                        <option value="{{ $d->district_id }}"
                        {{ $d->district_id == $loc->dist_id ? 'selected':'' }}>
                        {{ $d->dis_name }}
                        </option>
                    @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Kelurahan</label>
                    <select id="subdistrictSelect" name="subdis_id" class="form-control" required>
                    <option value="">Pilih Kelurahan</option>
                    @foreach($subdistricts as $sd)
                        <option value="{{ $sd->subdis_id }}"
                        {{ $sd->subdis_id == $property->subdis_id ? 'selected':'' }}>
                        {{ $sd->subdis_name }}
                        </option>
                    @endforeach
                    </select>
                </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="full_address" class="form-label">Alamat Selengkapnya</label>
                    <input type="text" class="form-control" id="full_address" name="alamat_selengkapnya" value="{{ $property->alamat_selengkapnya }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ $property->description }}</textarea>
                </div>

                <!-- Fasilitas -->
                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Fasilitas</label>
                    <div class="facilities-container position-relative mb-3">
                        <input
                            type="text"
                            class="form-control mb-2"
                            id="facilityInput"
                            placeholder="Cari fasilitas..."
                            autocomplete="off"
                        />
                        <div id="facilityDropdown" class="list-group autocomplete-dropdown"></div>

                        {{-- badge fasilitas terpilih --}}
                        <div id="selectedFacilities" class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($facilities as $facility)
                                <div
                                    class="facility-tag badge bg-primary d-flex align-items-center gap-1 px-2 py-1"
                                    data-id="{{ $facility->facility_id }}"
                                >
                                    <i class="bi bi-{{ $facility->icon }} me-2" style="font-size: 1.2rem;"></i>
                                    {{ $facility->facility_name }}
                                    <button type="button" class="btn-close btn-close-white btn-sm remove-facility ms-1"></button>
                                </div>
                            @endforeach
                        </div>

                        {{-- hidden inputs untuk form --}}
                        <div id="hiddenFacilitiesContainer">
                            @foreach($facilities as $facility)
                                <input
                                    type="hidden"
                                    name="facilities[]"
                                    value="{{ $facility->facility_id }}"
                                >
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-12">
                <label class="form-label">Pilih Lokasi Properti</label>
                <div id="map" style="width:100%;height:300px;border:1px solid #ddd;"></div>
                </div>

                {{-- Hidden inputs --}}
                <input 
                type="hidden" 
                id="latitude" 
                name="latitude" 
                value="{{ $property->latitude }}" 
                >
                <input 
                type="hidden" 
                id="longitude" 
                name="longitude" 
                value="{{ $property->longitude }}" 
                >
            </div>

                        <div class="mt-4 d-flex justify-content-end gap-3">
                <a href="{{ route('owner.property') }}" class="btn btn-secondary" style="border-radius: 20px;">Kembali</a>
                <button type="submit" style="border-radius: 20px;" class="btn btn-success">Simpan Perubahan</button>
            </div>
        </form>

            <hr>

            <style>
                .pagination {
                --bs-pagination-color: #0d6efd;
                --bs-pagination-bg: transparent;
                --bs-pagination-border-color: transparent;
            }

            .pagination .page-item .page-link {
                color: #0d6efd;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 4px;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
            }

            .pagination .page-item.active .page-link,
            .pagination .page-item .page-link:hover {
                background-color: #0d6efd;
                color: white;
                border-color: #0d6efd;
            }

            .pagination .page-item.disabled .page-link {
                color: #adb5bd;
                pointer-events: none;
                opacity: 0.6;
            }

            .pagination .page-link:focus {
                outline: 0;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            }
            </style>

            <div class="col-md-12 mb-4">
                <!-- Header & Tombol Tambah -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h5>Gambar Properti</h5>
                    <button type="button" style="border-radius: 20px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addImageModal">
                        <i class="bi bi-plus"></i> Tambah Gambar
                    </button>
                </div>
            
                <!-- Container Daftar Gambar -->
                <div id="image-list-container" class="row g-3 mt-3">
                    <!-- Item gambar akan diisi oleh JavaScript -->
                </div>
            
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-4 d-flex justify-content-center">
                    <ul class="pagination pagination-sm rounded-pill" id="pagination-controls">
                        <!-- Pagination buttons generated by JS -->
                    </ul>
                </nav>
            </div>
            
            <script>
                // Data gambar dari Blade ke JavaScript
                const images = @json($images); // Ambil semua gambar dari controller
                const itemsPerPage = 6; // Jumlah item per halaman
                let currentPage = 1;
            
                // Fungsi untuk menampilkan item berdasarkan halaman
                function displayItems(page) {
                const container = document.getElementById('image-list-container');
                container.innerHTML = ''; // Kosongkan kontainer
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const paginatedItems = images.slice(start, end);
                
                paginatedItems.forEach(image => {
                    const itemHTML = `
                        <div class="col-md-6 col-lg-4">
                            <div class="card-image d-flex align-items-center p-3 bg-white shadow-sm">
                                <img src="{{ asset('storage/') }}/${image.images_path}" 
                                    alt="Gambar Properti" width="100" class="me-3 rounded"
                                    style="object-fit: cover; height: 80px;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-truncate">Gambar Properti</h6>
                                    <small class="text-muted d-block text-truncate" style="max-width: 180px;">${image.images_path}</small>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm delete-image rounded-circle"
                                        data-id="${image.images_id}" data-path="${image.images_path}"
                                        title="Hapus Gambar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    container.innerHTML += itemHTML;
                });
            }

                            
                // Fungsi untuk membuat pagination buttons
                function setupPagination() {
                    const totalPages = Math.ceil(images.length / itemsPerPage);
                    const pagination = document.getElementById('pagination-controls');
                    pagination.innerHTML = '';
            
                    // Tombol Previous
                    const prevLi = document.createElement('li');
                    prevLi.className = 'page-item';
                    prevLi.innerHTML = `<a class="page-link" href="#">Previous</a>`;
                    prevLi.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (currentPage > 1) {
                            currentPage--;
                            displayItems(currentPage);
                            updatePagination();
                        }
                    });
                    pagination.appendChild(prevLi);
            
                    // Nomor halaman
                    for (let i = 1; i <= totalPages; i++) {
                        const li = document.createElement('li');
                        li.className = 'page-item';
                        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                        li.addEventListener('click', (e) => {
                            e.preventDefault();
                            currentPage = i;
                            displayItems(currentPage);
                            updatePagination();
                        });
                        pagination.appendChild(li);
                    }
            
                    // Tombol Next
                    const nextLi = document.createElement('li');
                    nextLi.className = 'page-item';
                    nextLi.innerHTML = `<a class="page-link" href="#">Next</a>`;
                    nextLi.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (currentPage < totalPages) {
                            currentPage++;
                            displayItems(currentPage);
                            updatePagination();
                        }
                    });
                    pagination.appendChild(nextLi);
            
                    updatePagination(); // Setel tampilan awal
                }
            
                // Fungsi untuk menyoroti halaman aktif
                function updatePagination() {
                    const totalPages = Math.ceil(images.length / itemsPerPage);
                    const buttons = document.querySelectorAll('#pagination-controls .page-item');

                    buttons.forEach((btn, index) => {
                        if (index === 0 || index === buttons.length - 1) return; // Skip Previous & Next
                        btn.classList.remove('active');
                    });

                    // Aktifkan halaman saat ini
                    const activeButton = buttons[1 + currentPage]; // karena index 0 = Previous
                    if (activeButton) {
                        activeButton.classList.add('active');
                    }
                }
            
                // Jalankan saat DOM siap
                document.addEventListener('DOMContentLoaded', () => {
                    displayItems(currentPage);
                    setupPagination();
                });
            </script>  

            <hr>
            <div class="col-md-12 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-semibold mb-0">Kelola Kamar</h4>
                    <button type="button" class="btn btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kamar
                    </button>
                </div>
            
                <!-- Daftar Kamar dalam Bentuk Tabel -->
                <div class="col-md-12 mb-4">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tipe Kamar</th>
                                <th scope="col">Harga / Malam</th>
                                <th scope="col">Total Room</th>
                                <th scope="col">Available Room</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $index => $room)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img 
                                            src="{{ asset('storage/' . $room->image_path) }}"
                                            alt="Foto {{ $room->room_type }}"
                                            class="rounded"
                                            style="width:80px; height:60px; object-fit:cover;"
                                        >
                                        <span class="ms-2">{{ $room->room_type }}</span>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($room->latest_price, 0, ',', '.') }}</td>
                                <td>{{ $room->total_room ?? $room->stok }}</td>
                                <td>{{ $room->available_room }}</td>
                                <td class="text-center">
                                    <a href="#" 
                                        class="btn btn-warning btn-sm rounded-circle me-1 edit-room-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editRoomModal"
                                        data-room_id="{{ $room->room_id }}"
                                        data-room_type="{{ $room->room_type }}"
                                        data-price="{{ $room->latest_price }}"
                                        data-stok="{{ $room->total_room ?? $room->stok }}"
                                        data-image_path="{{ asset('storage/' . $room->image_path) }}"
                                        title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    <form action="{{ route('property.room.delete', $room->room_id) }}"
                                        method="POST"
                                        class="d-inline delete-room-form">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                                class="btn btn-danger btn-sm confirm-delete rounded-circle"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada kamar ditambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>            
    </div>

    <!-- Modal Edit Kamar -->
    <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg rounded-4 border-0">
                <div class="modal-header bg-warning text-white rounded-top-4">
                    <h5 class="modal-title fw-semibold" id="editRoomModalLabel">Edit Kamar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <form id="editRoomForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_room_id" name="room_id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_room_type" class="form-label fw-medium">Tipe Kamar</label>
                                <input type="text" class="form-control" id="edit_room_type" name="room_type" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_price" class="form-label fw-medium">Harga per Malam</label>
                                <input type="text" class="form-control" id="edit_price" name="price" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_stok" class="form-label fw-medium">Kuantitas</label>
                                <input type="number" class="form-control" id="edit_stok" name="stok" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_room_image" class="form-label fw-medium">Gambar Kamar</label>
                                <input type="file" class="form-control" id="edit_room_image" name="room_image">
                                <div class="mt-2" id="edit_image_preview"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-warning px-4 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kamar -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg"> {{-- Lebih besar & tengah --}}
            <div class="modal-content shadow-lg rounded-4 border-0">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-semibold text-center" id="addRoomModalLabel">Tambah Kamar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <form id="addRoomForm" method="POST" action="{{ route('property.room.add') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="property_id" name="property_id" value="{{ $property->property_id }}">
    
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="room_type" class="form-label fw-medium">Tipe Kamar</label>
                                <input type="text" class="form-control" id="room_type" name="room_type" required placeholder="Contoh: Standard, Deluxe, Family, dll.">
                            </div>                            
                            <div class="col-md-6">
                                <label for="price" class="form-label fw-medium">Harga per Malam</label>
                                <input type="text" class="form-control" id="price" name="price" required placeholder="Rp 500.000">

                            </div>
                            <div class="col-md-6">
                                <label for="stok" class="form-label fw-medium">Kuantitas</label>
                                <input type="number" class="form-control" id="stok" name="stok" required>
                            </div>
                            <div class="col-md-6">
                                <label for="room_images" class="form-label fw-medium">Gambar Kamar</label>
                                <input type="file" class="form-control" id="room_images" name="room_images[]" multiple>
                            </div>
                        </div>
    
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-success px-4 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Gambar -->
    <div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="modalTitleAddImage" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-sm border-0">
                <div class="modal-header bg-light border-0 rounded-top-4 px-4 py-3">
                    <h5 class="modal-title fw-semibold" id="modalTitleAddImage">Tambah Gambar Properti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4 pt-2">
                    <form id="uploadImageForm" action="{{ route('property.image.add') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $property->property_id }}">

                        <div class="mb-3">
                            <label for="propertyImages" class="form-label fw-medium">Pilih Gambar</label>
                            <input type="file" class="form-control rounded-pill" id="propertyImages" name="images[]" multiple required>
                            <div class="form-text text-muted">Format: JPG, PNG, max 2MB per file</div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" id="uploadImageButton">
                                <i class="bi bi-cloud-upload me-1"></i> Unggah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Toast Notifikasi -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
        <div id="toast" class="toast bg-info text-white shadow-sm" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-transparent border-0 justify-content-between align-items-center">
                <strong class="me-auto d-flex align-items-center" id="toast-title">
                    <i class="bi bi-bell me-2"></i>Notifikasi
                </strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-body">
                <!-- Pesan akan muncul di sini -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#facilitySelect').select2({
                placeholder: 'Pilih fasilitas kamar',
                width: '100%'
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const toastElement = document.getElementById('toast');
            const toast = new bootstrap.Toast(toastElement);

            function showToast(message, type = 'info') {
                const toastBody = toastElement.querySelector('.toast-body');
                toastBody.textContent = message;
                toastElement.classList.remove('bg-success', 'bg-danger', 'bg-info');
                toastElement.classList.add(`bg-${type}`);
                toast.show();
            }

            // Hapus gambar
            document.querySelectorAll(".delete-image").forEach(button => {
                button.addEventListener("click", function () {
                    const imageId = this.getAttribute("data-id");
                    const imagePath = this.getAttribute("data-path");
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak dapat mengembalikan gambar ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ route('property.image.delete') }}", {
                                method: "DELETE",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ image_id: imageId, image_path: imagePath })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showToast('Gambar berhasil dihapus.', 'success');
                                    setTimeout(() => location.reload(), 1500);
                                } else {
                                    showToast('Gagal menghapus gambar: ' + data.message, 'danger');
                                }
                            })
                            .catch(error => console.error("Error:", error));
                        }
                    });
                });
            });

            // Hapus fasilitas
            document.getElementById("selectedFacilities").addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-facility")) {
                    const btn = e.target;
                    const facilityId = btn.closest('.facility-tag').dataset.id;

                    Swal.fire({
                        title: 'Yakin menghapus fasilitas?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ route('property.facility.delete') }}", {
                                method: "DELETE",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ facility_id: facilityId })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    showToast('Fasilitas berhasil dihapus.', 'success');
                                    loadFacilities(); // fungsi reload daftar fasilitas
                                } else {
                                    showToast('Gagal menghapus fasilitas: ' + data.message, 'danger');
                                }
                            })
                            .catch(() => showToast('Kesalahan jaringan saat hapus fasilitas.', 'danger'));
                        }
                    });
                }
            });

            // Tambah fasilitas
            document.getElementById("saveFacilities").addEventListener("click", function () {
                const facilityInputs = document.querySelectorAll(".facility-input");
                const facilities = [];
                facilityInputs.forEach(input => {
                    const value = input.value.trim();
                    if (value) facilities.push(value);
                });

                if (facilities.length === 0) {
                    showToast('Tambahkan setidaknya satu fasilitas.', 'warning');
                    return;
                }

                fetch("{{ route('property.addFacility') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        property_id: "{{ $property->property_id }}",
                        facilities: facilities
                    })
                })
                .then(response => response.json().catch(() => ({ success: false, message: 'Invalid JSON response' })))
                .then(data => {
                    if (data.success) {
                        const selectedFacilitiesDiv = document.getElementById("selectedFacilities");

                        facilities.forEach(facility => {
                            const newFacility = document.createElement("div");
                            newFacility.classList.add("facility-tag", "d-flex", "align-items-center", "gap-2", "me-2");
                            newFacility.setAttribute("data-id", data.facility_id);
                            newFacility.innerHTML = `
                                <i class="bi bi-check-circle text-success"></i>
                                <span>${facility}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-facility" data-id="${data.facility_id}">×</button>
                            `;
                            selectedFacilitiesDiv.appendChild(newFacility);
                        });

                        // Reset input form
                        document.getElementById("facility-container").innerHTML = `
                            <div class="input-group mb-2">
                                <input type="text" class="form-control facility-input" placeholder="Masukkan fasilitas baru">
                                <button class="btn btn-danger remove-facility" type="button">❌</button>
                            </div>
                        `;

                        // Tutup modal
                        const modalElement = document.getElementById("addFacilityModal");
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) modalInstance.hide();

                        showToast('Fasilitas berhasil ditambahkan.', 'success');
                    } else {
                        showToast('Gagal menambahkan fasilitas: ' + (data.message || "Unknown error"), 'danger');
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showToast('Terjadi kesalahan saat menyimpan fasilitas.', 'danger');
                });
            });

            // Muat fasilitas saat halaman dimuat
            window.addEventListener("load", function () {
                fetch("{{ route('property.get-selected-facilities', $property->property_id) }}")
                    .then(response => {
                        if (!response.ok) throw new Error('HTTP error! status: ' + response.status);
                        return response.json();
                    })
                    .then(data => {
                        const selectedFacilitiesDiv = document.getElementById("selectedFacilities");
                        selectedFacilitiesDiv.innerHTML = '';

                        if (!data || data.length === 0) {
                            selectedFacilitiesDiv.innerHTML = '<p class="text-muted ms-3">Belum ada fasilitas dipilih.</p>';
                            return;
                        }

                        data.forEach(facility => {
                            const facilityTag = document.createElement("div");
                            facilityTag.classList.add("facility-tag", "d-flex", "align-items-center", "gap-2", "me-2");
                            facilityTag.setAttribute("data-id", facility.id);
                            facilityTag.innerHTML = `
                                <i class="bi ${facility.icon || 'bi-building'} text-primary"></i>
                                <span>${facility.facility_name}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-facility" data-id="${facility.id}">×</button>
                            `;
                            selectedFacilitiesDiv.appendChild(facilityTag);
                        });
                    })
                    .catch(error => {
                        console.error("Error fetching selected facilities:", error);
                        document.getElementById("selectedFacilities").innerHTML = '<p class="text-danger">Gagal memuat fasilitas.</p>';
                    });
            });

            // Upload gambar
            document.getElementById("uploadImageForm").addEventListener("submit", function (event) {
                event.preventDefault();
                let uploadButton = document.getElementById("uploadImageButton");
                uploadButton.disabled = true;
                uploadButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengunggah...`;
                let formData = new FormData(this);
                let fileInput = document.getElementById("propertyImages");
                let allowedExtensions = ["jpg", "jpeg", "png", "webp"];
                let maxSize = 2 * 1024 * 1024;
                for (let file of fileInput.files) {
                    let ext = file.name.split('.').pop().toLowerCase();
                    if (!allowedExtensions.includes(ext)) {
                        showToast('Format file tidak didukung! Hanya jpg, jpeg, png, dan webp.', 'warning');
                        resetButton(uploadButton);
                        return;
                    }
                    if (file.size > maxSize) {
                        showToast('Ukuran file terlalu besar! Maksimal 2MB.', 'warning');
                        resetButton(uploadButton);
                        return;
                    }
                }
                fetch("{{ route('property.image.add') }}", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Gambar berhasil diunggah.', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('Gagal mengunggah gambar: ' + data.message, 'danger');
                        resetButton(uploadButton);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showToast('Terjadi kesalahan saat mengunggah gambar.', 'danger');
                    resetButton(uploadButton);
                });
            });

            function resetButton(button) {
                button.disabled = false;
                button.innerHTML = "Unggah";
            }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Saat modal edit dibuka
    $('#editRoomModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const modal  = $(this);

        // Isi data input
        modal.find('#edit_room_id').val(button.data('room_id'));
        modal.find('#edit_room_type').val(button.data('room_type'));
        modal.find('#edit_price').val(button.data('price'));
        modal.find('#edit_stok').val(button.data('stok'));
        if (button.data('image_path')) {
            modal.find('#edit_image_preview').html('<img src="' + button.data('image_path') + '" style="max-width:80px;max-height:60px;" class="rounded">');
        } else {
            modal.find('#edit_image_preview').html('');
        }

        // Ganti action form
        const propertyId = '{{ $property->property_id }}';
        const roomId = button.data('room_id');
        modal.find('#editRoomForm').attr('action', `/property/${propertyId}/room/${roomId}/update`);
    });

    // Format input harga
    const editPriceInput = document.getElementById('edit_price');
    editPriceInput.addEventListener('input', e => {
        let nums = e.target.value.replace(/\D/g, '');
        e.target.value = nums ? 'Rp ' + new Intl.NumberFormat('id-ID').format(nums) : '';
    });
    document.getElementById('editRoomForm').addEventListener('submit', function(e){
        editPriceInput.value = editPriceInput.value.replace(/\D/g, '');
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const priceInput = document.getElementById('price');
  const addRoomForm = document.getElementById('addRoomForm');

  priceInput.addEventListener('input', e => {
    // ambil hanya angka
    let nums = e.target.value.replace(/\D/g, '');
    // format dan tambahkan prefix Rp
    e.target.value = nums
      ? 'Rp ' + new Intl.NumberFormat('id-ID').format(nums)
      : '';
  });

  addRoomForm.addEventListener('submit', e => {
    // sebelum submit, hilangkan semua non-digit
    priceInput.value = priceInput.value.replace(/\D/g, '');
  });
});
</script>


<script>
    // Fungsi showToast
    const toastElement = document.getElementById('toast');
    const bsToast = new bootstrap.Toast(toastElement);
    const toastTitle = document.getElementById('toast-title');
    const toastBody = document.getElementById('toast-body');

    function showToast(message, type = 'info', title = 'Notifikasi') {
        const bgClassMap = {
            'success': 'bg-success',
            'danger': 'bg-danger',
            'warning': 'bg-warning',
            'info': 'bg-primary'
        };

        const iconMap = {
            'success': '<i class="bi bi-check-circle me-2"></i>',
            'danger': '<i class="bi bi-exclamation-triangle me-2"></i>',
            'warning': '<i class="bi bi-exclamation-lg me-2"></i>',
            'info': '<i class="bi bi-info-circle me-2"></i>'
        };

        // Reset kelas warna
        toastElement.className = 'toast position-fixed';
        toastElement.classList.add(bgClassMap[type] || 'bg-info', 'text-white', 'shadow-sm');

        // Set ikon dan judul
        toastTitle.innerHTML = `${iconMap[type] || ''}${title}`;

        // Set pesan
        toastBody.innerHTML = message;

        // Tampilkan toast
        bsToast.show();
    }

    // Hapus gambar dengan soft delete (update is_deleted = 1)
    document.getElementById('image-list-container').addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-image');
        if (!btn) return;

        const imageId = btn.getAttribute("data-id");
        const imagePath = btn.getAttribute("data-path");

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Gambar akan ditandai sebagai terhapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('property.image.delete') }}", {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ image_id: imageId, image_path: imagePath })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Server error');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast('Gambar berhasil ditandai sebagai dihapus.', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('Gagal menandai gambar: ' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showToast('Terjadi kesalahan saat memperbarui gambar.', 'danger');
                });
            }
        });
    });
</script>

{{-- Leaflet JS --}}
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Ambil koordinat awal, fallback ke tengah kota jika kosong
      let lat = parseFloat('{{ $property->latitude }}');
      let lng = parseFloat('{{ $property->longitude }}');
      if (isNaN(lat) || isNaN(lng)) {
        lat = -6.200000;  // Jakarta default
        lng = 106.816666;
      }

      // Inisialisasi peta
      const map = L.map('map').setView([lat, lng], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      // Buat marker (draggable)
      let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

      // Fungsi update hidden inputs
      function updateCoords(latlng) {
        document.getElementById('latitude').value  = latlng.lat.toFixed(6);
        document.getElementById('longitude').value = latlng.lng.toFixed(6);
      }

      // Update saat drag selesai
      marker.on('dragend', function(e) {
        updateCoords(e.target.getLatLng());
      });

      // Saat user klik di peta: pindahkan marker atau buat baru
      map.on('click', function(e) {
        const pos = e.latlng;
        marker.setLatLng(pos);     // pindahkan marker
        updateCoords(pos);
        // kalau awalnya marker null, bisa:
        // marker = L.marker(pos, { draggable: true }).addTo(map);
      });
    });
  </script>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('facilityInput');
    const dropdown = document.getElementById('facilityDropdown');
    const selectedDiv = document.getElementById('selectedFacilities');
    const hiddenContainer = document.getElementById('hiddenFacilitiesContainer');

    let facilities = []; // cache suggestion

    // Fetch list facility (bisa endpoint your-route)
    function loadFacilities() {
    fetch("{{ route('property.get-selected-facilities', $property->property_id) }}")
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('selectedFacilities');
            const hiddenBox = document.getElementById('hiddenFacilitiesContainer');
            container.innerHTML = '';
            hiddenBox.innerHTML = '';

            if (!data || data.length === 0) {
                container.innerHTML = '<p class="text-muted ms-3">Belum ada fasilitas dipilih.</p>';
                return;
            }

            data.forEach(facility => {
                const div = document.createElement('div');
                div.className = 'facility-tag badge bg-primary d-flex align-items-center gap-1 px-2 py-1';
                div.dataset.id = facility.id;
                div.innerHTML = `
                    <i class="bi bi-${facility.icon || 'building'} me-2" style="font-size:1.2rem;"></i>
                    ${facility.facility_name}
                    <button type="button" class="btn-close btn-close-white btn-sm remove-facility ms-1"></button>
                `;
                container.appendChild(div);

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'facilities[]';
                hiddenInput.value = facility.id;
                hiddenBox.appendChild(hiddenInput);
            });
        });
}

    // Render dropdown filtered
    input.addEventListener('input', () => {
      const term = input.value.toLowerCase().trim();
      dropdown.innerHTML = '';
      if (!term) return;
      facilities
        .filter(f => f.name.toLowerCase().includes(term))
        .slice(0, 10)
        .forEach(f => {
          const item = document.createElement('button');
          item.type = 'button';
          item.className = 'list-group-item list-group-item-action';
          item.textContent = f.name;
          item.dataset.id = f.id;
          dropdown.appendChild(item);
        });
    });

    // Pilih dari dropdown
    dropdown.addEventListener('click', e => {
      if (!e.target.dataset.id) return;
      const id = e.target.dataset.id;
      const name = e.target.textContent;

      // Cegah duplikasi
      if (hiddenContainer.querySelector(`input[value="${id}"]`)) {
        input.value = '';
        dropdown.innerHTML = '';
        return;
      }

      // Tambah tag
      const tag = document.createElement('div');
      tag.className = 'badge bg-primary d-flex align-items-center gap-1 px-2 py-1';
      tag.innerHTML = `
        ${name}
        <button type="button" class="btn-close btn-close-white btn-sm ms-1 remove-facility"></button>
      `;
      tag.dataset.id = id;
      selectedDiv.appendChild(tag);

      // Hidden input
      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'facilities[]';
      hidden.value = id;
      hiddenContainer.appendChild(hidden);

      // Bersihkan
      input.value = '';
      dropdown.innerHTML = '';
    });

    // Hapus tag
    selectedDiv.addEventListener('click', e => {
      if (!e.target.classList.contains('remove-facility')) return;
      const tag = e.target.closest('.badge');
      const id = tag.dataset.id;
      // Hapus tag & hidden
      tag.remove();
      const hidden = hiddenContainer.querySelector(`input[value="${id}"]`);
      if (hidden) hidden.remove();
    });

    // Tutup dropdown klik luar
    document.addEventListener('click', e => {
      if (!input.contains(e.target)) dropdown.innerHTML = '';
    });
  });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const allFacilities = @json($allFacilities); // Semua fasilitas dari controller
        const input = document.getElementById('facilityInput');
        const dropdown = document.getElementById('facilityDropdown');
        const selected = document.getElementById('selectedFacilities');
        const hiddenBox = document.getElementById('hiddenFacilitiesContainer');

        // Tampilkan dropdown jika ada input
        input.addEventListener('input', () => {
            const term = input.value.trim().toLowerCase();
            dropdown.innerHTML = '';
            if (!term) {
                dropdown.style.display = 'none';
                return;
            }

            const filtered = allFacilities
                .filter(f => f.facility_name.toLowerCase().includes(term))
                .slice(0, 10);

            if (filtered.length === 0) {
                dropdown.style.display = 'none';
                return;
            }

            filtered.forEach(f => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'list-group-item list-group-item-action';
                btn.textContent = f.facility_name;
                btn.dataset.id = f.id || f.facility_id;
                btn.dataset.icon = f.icon || '';
                dropdown.appendChild(btn);
            });

            dropdown.style.display = 'block';
        });

        // Klik pilihan dropdown
        dropdown.addEventListener('click', e => {
            if (e.target.tagName !== 'BUTTON') return;
            const id = e.target.dataset.id;
            const name = e.target.textContent;
            const icon = e.target.dataset.icon;

            // Cegah duplikat
            if (hiddenBox.querySelector(`input[value="${id}"]`)) {
                input.value = '';
                dropdown.style.display = 'none';
                return;
            }

            // Buat badge baru
            const badge = document.createElement('div');
            badge.className = 'facility-tag badge bg-primary d-flex align-items-center gap-1 px-2 py-1';
            badge.dataset.id = id;
            badge.innerHTML = `
                <i class="bi bi-${icon} me-2" style="font-size: 1.2rem;"></i>
                ${name}
                <button type="button" class="btn-close btn-close-white btn-sm remove-facility ms-1"></button>
            `;
            selected.appendChild(badge);

            // Buat hidden input
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'facilities[]';
            hidden.value = id;
            hiddenBox.appendChild(hidden);

            // Bersihkan input dan dropdown
            input.value = '';
            dropdown.style.display = 'none';
        });

        // Hapus badge & hidden input
        selected.addEventListener('click', e => {
            if (!e.target.classList.contains('remove-facility')) return;
            const badge = e.target.closest('.facility-tag');
            const id = badge.dataset.id;
            badge.remove();
            const hid = hiddenBox.querySelector(`input[value="${id}"]`);
            if (hid) hid.remove();
        });

        // Klik di luar untuk tutup dropdown
        document.addEventListener('click', e => {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  const provSel  = document.getElementById('provinceSelect');
  const regSel   = document.getElementById('regencySelect');
  const distSel  = document.getElementById('districtSelect');
  const subdSel  = document.getElementById('subdistrictSelect');

  async function fillWeb(url, elm, placeholder) {
    elm.innerHTML = `<option>Memuat…</option>`;
    try {
      const res = await fetch(url, {
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      });
      if (!res.ok) {
        elm.innerHTML = `<option value="">Gagal memuat (${res.status})</option>`;
        return;
      }
      const data = await res.json();
      // unwrap kalau dibungkus: { cities: […] } atau langsung array
      const list = data.cities || data.districts || data.subdistricts || data;

      elm.innerHTML = `<option value="">${placeholder}</option>`;
      if (!Array.isArray(list) || list.length === 0) {
        elm.innerHTML = `<option value="">Tidak ada data</option>`;
        return;
      }

      list.forEach(item => {
        // cari key yang berakhiran _id atau 'id'
        const idKey   = Object.keys(item)
                         .find(k => k.toLowerCase().endsWith('_id') || k.toLowerCase()==='id');
        // cari key yang berakhiran _name atau 'name'
        const nameKey = Object.keys(item)
                         .find(k => k.toLowerCase().endsWith('_name') || k.toLowerCase()==='name');
        if (!idKey || !nameKey) return;

        const opt = document.createElement('option');
        opt.value = item[idKey];
        opt.text  = item[nameKey];
        elm.appendChild(opt);
      });
    } catch (err) {
      console.error('fillWeb error:', err);
      elm.innerHTML = `<option value="">Gagal memuat: ${err.message}</option>`;
    }
  }

  provSel.addEventListener('change', () => {
    if (!provSel.value) {
      regSel.innerHTML  = `<option value="">Pilih Kota/Kabupaten</option>`;
      distSel.innerHTML = `<option value="">Pilih Kecamatan</option>`;
      subdSel.innerHTML = `<option value="">Pilih Kelurahan</option>`;
      return;
    }
    fillWeb(`/cities/${provSel.value}`, regSel, 'Pilih Kota/Kabupaten');
    distSel.innerHTML = `<option value="">Pilih Kecamatan</option>`;
    subdSel.innerHTML = `<option value="">Pilih Kelurahan</option>`;
  });

  regSel.addEventListener('change', () => {
    if (!regSel.value) {
      distSel.innerHTML = `<option value="">Pilih Kecamatan</option>`;
      subdSel.innerHTML = `<option value="">Pilih Kelurahan</option>`;
      return;
    }
    fillWeb(`/districts/${regSel.value}`, distSel, 'Pilih Kecamatan');
    subdSel.innerHTML = `<option value="">Pilih Kelurahan</option>`;
  });

  distSel.addEventListener('change', () => {
    if (!distSel.value) {
      subdSel.innerHTML = `<option value="">Pilih Kelurahan</option>`;
      return;
    }
    fillWeb(`/subdistricts/${distSel.value}`, subdSel, 'Pilih Kelurahan');
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
  document.body.addEventListener('click', function(e){
    const btn = e.target.closest('.confirm-delete');
    if (!btn) return;
    e.preventDefault();

    // cari form delete‐room terdekat
    const form = btn.closest('.delete-room-form');

    Swal.fire({
      title: 'Yakin menghapus kamar ini?',
      text: "Data kamar akan dihapus permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then(result => {
      if (result.isConfirmed) {
        form.submit();  // hanya submit form delete‐room
      }
    });
  });
});
</script>

<script>
    $(function(){
  const allFacilities = @json($allFacilities);
  const input = $('#facilityInput');
  const dropdown = $('#facilityDropdown');
  const selected = $('#selectedFacilities');
  const hiddenBox = $('#hiddenFacilitiesContainer');

  // Fungsi tampilkan dropdown filter
  input.on('input', function(){
    const term = $(this).val().trim().toLowerCase();
    dropdown.empty();
    if(!term) {
      dropdown.hide();
      return;
    }
    let filtered = allFacilities.filter(f => f.facility_name.toLowerCase().includes(term));
    if(filtered.length === 0){
      dropdown.hide();
      return;
    }
    filtered.slice(0, 10).forEach(f => {
      dropdown.append(`<div class="autocomplete-item" data-id="${f.facility_id}" data-icon="${f.icon}">${f.facility_name}</div>`);
    });
    dropdown.show();
  });

  // Klik pilih fasilitas di dropdown
  dropdown.on('click', '.autocomplete-item', function(){
    const id = $(this).data('id');
    const name = $(this).text();
    const icon = $(this).data('icon') || 'building';

    // Cegah duplikat
    if(hiddenBox.find(`input[value="${id}"]`).length > 0){
      input.val('');
      dropdown.hide();
      return;
    }

    // Tambah tag badge
    const tag = $(`
      <div class="facility-tag badge bg-primary d-flex align-items-center gap-1 px-2 py-1" data-id="${id}">
        <i class="bi bi-${icon} me-2" style="font-size: 1.2rem;"></i>
        ${name}
        <button type="button" class="btn-close btn-close-white btn-sm remove-facility ms-1"></button>
      </div>
    `);
    selected.append(tag);

    // Tambah hidden input
    hiddenBox.append(`<input type="hidden" name="facilities[]" value="${id}">`);

    // Reset input dan dropdown
    input.val('');
    dropdown.hide();
  });

  // Hapus fasilitas yang dipilih
  selected.on('click', '.remove-facility', function(){
    const parent = $(this).closest('.facility-tag');
    const id = parent.data('id');
    parent.remove();
    hiddenBox.find(`input[value="${id}"]`).remove();
  });

  // Klik di luar dropdown untuk sembunyikan dropdown
  $(document).on('click', function(e){
    if(!input.is(e.target) && !dropdown.is(e.target) && dropdown.has(e.target).length === 0){
      dropdown.hide();
    }
  });
});

</script>

@endsection
