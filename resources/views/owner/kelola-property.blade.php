@extends('layouts.owner.index-owner')
@section('content')
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
                    <label for="name" class="form-label">Nama Properti</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $property->property_name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="property_type" class="form-label">Kategori</label>
                    <select class="form-control" id="property_type" name="property_type" required>
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type->id }}" {{ $type->id == $property->property_type_id ? 'selected' : '' }}>{{ $type->property_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="subdistrict" class="form-label">Alamat</label>
                    <select class="form-control" id="subdistrict" name="subdis_id" required>
                        @foreach($subdistricts as $sub)
                            <option value="{{ $sub->id }}" {{ $sub->id == $property->subdis_id ? 'selected' : '' }}>{{ $sub->subdis_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ $property->description }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-medium">Fasilitas</label>
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-3">
                            <h6 class="mb-3 text-secondary">Fasilitas Terpilih</h6>
                            <div class="selected-facilities d-flex flex-wrap gap-2" id="selectedFacilities">
                                <!-- Fasilitas akan ditambahkan secara dinamis -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            
                <div id="room-list" class="row g-3">
                    @forelse($rooms as $room)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body d-flex">
                                    <img src="{{ asset('storage/' . $room->image_path) }}"
                                         alt="Room Image"
                                         class="rounded-3 me-3"
                                         style="width: 100px; height: 80px; object-fit: cover;">
            
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">{{ $room->room_type }}</h6>
                                        <small class="text-muted d-block mb-1">Rp {{ number_format($room->latest_price, 0, ',', '.') }} / malam</small>
                                        <small class="text-secondary">Jumlah: {{ $room->stok }} kamar</small>
                                    </div>
            
                                    <div class="d-flex align-items-center ms-2 gap-2">
                                        <a href="" class="btn btn-warning btn-sm rounded-circle" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('property.room.delete', $room->room_id) }}" method="POST" class="delete-room-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm rounded-circle confirm-delete" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-light border text-center" role="alert">
                                Belum ada kamar ditambahkan.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>            

            <div class="mt-4 d-flex justify-content-end gap-3">
                <a href="{{ route('owner.property') }}" class="btn btn-secondary" style="border-radius: 20px;">Kembali</a>
                <button type="submit" style="border-radius: 20px;" class="btn btn-success">Simpan Perubahan</button>
            </div>
        </form>
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
    
    <!-- Modal Tambah Fasilitas -->
    <div class="modal fade" id="addFacilityModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="facility-container">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control facility-input" placeholder="Masukkan fasilitas baru">
                            <button class="btn btn-danger remove-facility" type="button">❌</button>
                        </div>
                    </div>
                    <button type="button" style="border-radius: 20px;" class="btn btn-success w-100" id="addMoreFacility">+ Tambah Fasilitas</button>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-radius: 20px;" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" style="border-radius: 20px;" class="btn btn-primary" id="saveFacilities">Simpan</button>
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
                if (e.target.classList.contains("delete-facility") || e.target.closest(".delete-facility")) {
                    const button = e.target.closest(".delete-facility");
                    const facilityId = button.dataset.id;

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak dapat mengembalikan fasilitas ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
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
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showToast('Fasilitas berhasil dihapus.', 'success');
                                    button.closest('.facility-tag').remove();
                                } else {
                                    showToast('Gagal menghapus fasilitas: ' + data.message, 'danger');
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                                showToast('Terjadi kesalahan saat menghapus fasilitas.', 'danger');
                            });
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

            // Hapus kamar
            document.addEventListener('click', function (e) {
            document.addEventListener('click', function (e) {
            if (e.target.closest('.confirm-delete')) {
                const button = e.target.closest('.confirm-delete');
                const form = button.closest('form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data kamar akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed && form) {
                        form.submit(); // Kirim form DELETE ke server
                    }
                });
            }
});

});

        });

        // Format harga
        const priceInput = document.getElementById('price');
        priceInput.addEventListener('input', function (e) {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) this.value = new Intl.NumberFormat('id-ID').format(value);
            else this.value = '';
        });

        document.getElementById('price').addEventListener('input', function (e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 9) value = value.slice(0, 9);
            this.value = new Intl.NumberFormat('id-ID').format(value);
        });

        document.getElementById('addRoomForm').addEventListener('submit', function (e) {
            const priceField = document.getElementById('price');
            priceField.value = priceField.value.replace(/\./g, ''); // hapus titik agar jadi angka murni
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
@endsection