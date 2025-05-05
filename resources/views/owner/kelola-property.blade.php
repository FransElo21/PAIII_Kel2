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

        .swal2-popup {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .swal2-title {
            color: #000000 !important;
        }
        .swal2-content {
            color: #000000 !important;
        }
        .swal2-confirm {
            background-color: #3085d6 !important;
        }
        .form-control{
            border-radius: 20px;
        }

    </style>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb align-items-center">
          <li class="breadcrumb-item">
            <a href="/property">
               Property
            </a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
             Kelola Property
          </li>
        </ol>
      </nav>
        <div class="card">
            <h3 class="text-center mb-4">Kelola Properti</h3>
            <form action="{{ route('owner.update-property', $property->property_id) }}" method="POST" enctype="multipart/form-data" id="propertyForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Properti</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $property->property_name }}" required>
                        <div class="invalid-feedback">Nama properti harus diisi.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="property_type" class="form-label">Kategori</label>
                        <select class="form-control" id="property_type" name="property_type" required>
                            @foreach($propertyTypes as $type)
                                <option value="{{ $type->id }}" {{ $type->id == $property->property_type_id ? 'selected' : '' }}>{{ $type->property_type }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Kategori harus dipilih.</div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="subdistrict" class="form-label">Alamat</label>
                    <select class="form-control" id="subdistrict" name="subdis_id" required>
                        @foreach($subdistricts as $sub)
                            <option value="{{ $sub->id }}" {{ $sub->id == $property->subdis_id ? 'selected' : '' }}>
                                {{ $sub->subdis_name }}
                            </option>
                        @endforeach
                    </select>                    
                    <div class="invalid-feedback">Alamat harus dipilih.</div>
                </div>                              
                
                <div class="col-md-6 mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{ $property->description }}</textarea>
                    <div class="invalid-feedback">Deskripsi harus diisi.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fasilitas</label>
                    <select name="facility_ids[]" id="facilitySelect" class="form-control" multiple required>
                        {{-- @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}"
                                {{ in_array($facility->id, $roomFacilities) ? 'selected' : '' }}>
                                {{ $facility->facility_name }}
                            </option>
                        @endforeach --}}
                    </select>
                </div>                                              
                <hr>
                <div class="col-md-12 mb-4">
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h5>Gambar Properti</h5>
                        <button type="button" style="border-radius: 20px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addImageModal">
                            <i class="bi bi-plus"></i> Tambah Gambar
                        </button>
                    </div>
                
                    <div id="image-list" class="list-group mt-3">
                        @foreach($images as $image)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $image->images_path) }}" alt="Gambar Properti" width="100" class="me-3 rounded" style="object-fit: cover; height: 70px;">
                                    <div>
                                        <h6 class="mb-1">Gambar Properti</h6>
                                        <small>{{ $image->images_path }}</small>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-danger btn-sm rounded-circle delete-image" data-id="{{ $image->images_id }}" data-path="{{ $image->images_path }}">
                                        <i class="bi bi-trash"></i>
                                </div>                                
                            </div>
                        @endforeach
                    </div>           
                </div>           
                <hr>
                <div class="col-md-12 mb-4">
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h5>Kelola Kamar</h5>
                        <button type="button" style="border-radius: 20px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                            <i class="bi bi-plus"></i> Tambah Kamar
                        </button>
                    </div>
                
                    <div id="room-list" class="list-group mt-3">
                        @foreach($rooms as $room)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $room->image_path) }}" alt="Room Image" width="100" class="me-3 rounded" style="object-fit: cover; height: 70px;">
                                    <div>
                                        <h6 class="mb-1">{{ $room->room_type }}</h6>
                                        <small>Rp {{ number_format($room->latest_price, 0, ',', '.') }}</small><br>
                                        <small>Jumlah: {{ $room->stok }} kamar</small>
                                    </div>
                                </div>
                                <div>
                                    <a href="" class="btn btn-warning btn-sm rounded-circle" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form id="delete-form-" action="" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="property_id" value="">
                                        <button type="button" class="btn btn-danger btn-sm rounded-circle" onclick="confirmDelete()" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form> 
                                </div>
                            </div>
                        @endforeach
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoomModalLabel">Tambah Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addRoomForm" method="POST" action="{{ route('property.room.add') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="property_id" name="property_id" value="{{ $property->property_id }}">

                        <div class="mb-3">
                            <label for="room_type" class="form-label">Tipe Kamar</label>
                            <select class="form-control" id="room_type" name="room_type" required>
                                <option value="Standard">Standard</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Suite">Suite</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga per Malam</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Kuantitas</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>

                        <div class="mb-3">
                            <label for="room_images" class="form-label">Gambar Kamar</label>
                            <input type="file" class="form-control" id="room_images" name="room_images[]" multiple>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleAddImage">Tambah Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadImageForm" action="{{ route('property.image.add') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $property->property_id }}">
                        <div class="mb-3">
                            <label for="propertyImages" class="form-label">Pilih Gambar</label>
                            <input type="file" class="form-control" id="propertyImages" name="images[]" multiple required>
                        </div>
                        <button type="submit" class="btn btn-primary" id="uploadImageButton">Unggah</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifikasi -->
    <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notifikasi</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
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
    </script>
    

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toastElement = document.getElementById('toast');
            const toast = new bootstrap.Toast(toastElement);

            function showToast(message, type = 'info') {
                const toastBody = toastElement.querySelector('.toast-body');
                toastBody.textContent = message;
                toastElement.classList.remove('bg-success', 'bg-danger', 'bg-info');
                toastElement.classList.add(`bg-${type}`);
                toast.show();
            }

            document.querySelectorAll(".delete-image").forEach(button => {
                button.addEventListener("click", function() {
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

            document.querySelectorAll(".delete-facility").forEach(button => {
                button.addEventListener("click", function() {
                    const facilityId = this.getAttribute("data-id");

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
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ 
                                    facility_id: facilityId,
                                    _method: "DELETE"
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showToast('Fasilitas berhasil dihapus.', 'success');
                                    setTimeout(() => location.reload(), 1500);
                                } else {
                                    showToast('Gagal menghapus fasilitas: ' + data.message, 'danger');
                                }
                            })
                            .catch(error => console.error("Error:", error));
                        }
                    });
                });
            });

            document.getElementById("addMoreFacility").addEventListener("click", function () {
                const facilityContainer = document.getElementById("facility-container");
                const newInputGroup = document.createElement("div");
                newInputGroup.classList.add("input-group", "mb-2");
                newInputGroup.innerHTML = `
                    <input type="text" class="form-control facility-input" placeholder="Masukkan fasilitas baru">
                    <button class="btn btn-danger remove-facility" type="button">❌</button>
                `;
                facilityContainer.appendChild(newInputGroup);
            });

            document.addEventListener("click", function (event) {
                if (event.target.classList.contains("remove-facility")) {
                    event.target.parentElement.remove();
                }
            });

            document.getElementById("saveFacilities").addEventListener("click", function () {
                const facilityInputs = document.querySelectorAll(".facility-input");
                const facilities = [];

                facilityInputs.forEach(input => {
                    const value = input.value.trim();
                    if (value) {
                        facilities.push(value);
                    }
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
                        facilities.forEach(facility => {
                            const newFacility = document.createElement("div");
                            newFacility.classList.add("list-group-item");
                            newFacility.innerHTML = `
                                <span>${facility}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-facility">❌</button>
                            `;
                            document.getElementById("facilities-list").appendChild(newFacility);
                        });

                        document.getElementById("facility-container").innerHTML = `
                            <div class="input-group mb-2">
                                <input type="text" class="form-control facility-input" placeholder="Masukkan fasilitas baru">
                                <button class="btn btn-danger remove-facility" type="button">❌</button>
                            </div>
                        `;
                        const modalElement = document.getElementById("addFacilityModal");
                        const modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if (modalInstance) {
                            modalInstance.hide();
                        }

                        showToast('Fasilitas berhasil ditambahkan.', 'success');
                    } else {
                        showToast('Gagal menambahkan fasilitas: ' + (data.message || "Unknown error"), 'danger');
                    }
                })
                .catch(error => console.error("Error:", error));
            });

            document.getElementById("uploadImageForm").addEventListener("submit", function(event) {
                event.preventDefault();

                let uploadButton = document.getElementById("uploadImageButton");
                uploadButton.disabled = true;
                uploadButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengunggah...`;

                let formData = new FormData(this);
                let fileInput = document.getElementById("propertyImages");
                let allowedExtensions = ["jpg", "jpeg", "png", "webp"];
                let maxSize = 2 * 1024 * 1024; // 2MB

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
        const priceInput = document.getElementById('price');
    
        priceInput.addEventListener('input', function (e) {
            let value = this.value.replace(/[^\d]/g, ''); // hapus semua selain angka
            if (value) {
                this.value = new Intl.NumberFormat('id-ID').format(value);
            } else {
                this.value = '';
            }
        });
    </script>
    <script>
        document.querySelector('button[data-bs-target="#addImageModal"]').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('addImageModal'));
            modal.show();
        });
    </script>
    
@endsection
