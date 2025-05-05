@extends('layouts.owner.index-owner')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    /* Map Styles */
    #map {
        height: 350px;
        border-radius: 12px;
        margin-top: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-gps {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000;
        background-color: #0d6efd;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    /* Facilities Styles */
    .facilities-container {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 10px;
    }
    
    .selected-facilities {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        min-height: 40px;
        margin-bottom: 10px;
    }
    
    .facility-tag {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        background-color: #e9ecef;
        border-radius: 20px;
        font-size: 14px;
    }
    
    .remove-facility {
        margin-left: 8px;
        cursor: pointer;
        color: #6c757d;
        font-weight: bold;
    }
    
    .remove-facility:hover {
        color: #dc3545;
    }
    
    .autocomplete-dropdown {
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ced4da;
        border-top: none;
        border-radius: 0 0 5px 5px;
        z-index: 1000;
        display: none;
    }
    
    .autocomplete-item {
        padding: 8px 12px;
        cursor: pointer;
    }
    
    .autocomplete-item:hover {
        background-color: #f8f9fa;
    }
    .breadcrumb {
        background-color: transparent;
        padding: 0;
        margin-bottom: 1.2rem;
        font-size: 0.95rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        color: #6c757d;
        padding: 0 0.5rem;
        }

        .breadcrumb a {
        color: #0d6efd;
        text-decoration: none;
        }

        .breadcrumb a:hover {
        text-decoration: underline;
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
         Tambah Property
      </li>
    </ol>
  </nav>
  
<div class="card">
    <div class="card-body">
        <h4 class="mb-4">Tambah Properti</h4>
        <form action="{{route('owner.store-property')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama Properti -->
            <div class="mb-3">
                <label for="name" class="form-label">Nama Properti</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama properti" style="border-radius: 20px" required>
            </div>

            <!-- Property Type -->
            <div class="mb-3">
                <label for="property_type" class="form-label">Tipe Properti</label>
                <select class="form-select" id="property_type" name="property_type_id" style="border-radius: 20px" required>
                    <option value="" disabled selected>Pilih tipe properti</option>
                    @foreach ($propertyTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->property_type }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Location Fields -->
            <div class="mb-3">
                <label for="province" class="form-label">Provinsi</label>
                <select class="form-select" id="province" name="province" style="border-radius: 20px" required>
                    <option value="" disabled selected>Pilih Provinsi</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="city" class="form-label">Kota/Kabupaten</label>
                <select class="form-select" id="city" name="city" style="border-radius: 20px" disabled required>
                    <option value="" disabled selected>Pilih Kota</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="district" class="form-label">Kecamatan</label>
                <select class="form-select" id="district" name="district" style="border-radius: 20px" disabled required>
                    <option value="" disabled selected>Pilih Kecamatan</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="subdistrict" class="form-label">Kelurahan</label>
                <select class="form-select" id="subdistrict" name="subdis_id" style="border-radius: 20px" disabled required>
                    <option value="" disabled selected>Pilih Kelurahan</option>
                </select>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Deskripsi properti" style="border-radius: 20px" required></textarea>
            </div>

            <!-- Facilities -->
            <div class="mb-3">
                <label for="facilities" class="form-label">Fasilitas</label>
                <div class="facilities-container">
                    <div class="mb-3 position-relative">
                        <label for="facilityInput" class="form-label">Cari dan Pilih Fasilitas</label>
                        <input type="text" class="form-control" id="facilityInput" style="border-radius: 20px" placeholder="Cari fasilitas...">
                        <div class="autocomplete-dropdown" id="facilityDropdown"></div>
                    </div>
            
                    <div class="selected-facilities" id="selectedFacilities"></div>
            
                    <!-- Hidden input untuk menyimpan ID fasilitas terpilih -->
                    <div id="hiddenFacilitiesContainer"></div>
                </div>
            </div>                                            

            <!-- Images -->
            <div class="mb-3">
                <label class="form-label">Upload Gambar</label>
                <div id="image-list">
                    <div class="input-group mb-2">
                        <input type="file" name="images[]" class="form-control" accept="image/*" style="border-radius: 20px" multiple required>
                        <button type="button" class="btn btn-danger remove-image">
                            <span class="material-icons-outlined">delete</span>
                        </button>                        
                    </div>
                </div>
                {{-- <button type="button" class="btn btn-secondary mt-2" id="add-image">Tambah Gambar</button> --}}
            </div>

            <!-- Map -->
            <label class="form-label">Koordinat</label>
            <div id="map" class="mb-3"></div>
            <input type="text" hidden class="form-control" id="latitude" name="latitude" readonly>
            <input type="text" hidden class="form-control" id="longitude" name="longitude" readonly>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success mt-3" style="border-radius: 20px">Tambah Properti</button>
        </form>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Location Dropdown Logic
$(document).ready(function () {
    // Get provinces
    $.ajax({
        url: '/provinces',
        type: 'GET',
        success: function (data) {
            $.each(data, function (key, province) {
                $('#province').append('<option value="' + province.id + '">' + province.prov_name + '</option>');
            });
        }
    });

    // Province change
    $('#province').on('change', function () {
        var province_id = $(this).val();
        $('#city, #district, #subdistrict')
            .html('<option value="" disabled selected>Pilih</option>')
            .prop('disabled', true);

        if (province_id) {
            $.get('/cities/' + province_id, function (data) {
                if (data.length > 0) {
                    $.each(data, function (key, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.city_name + '</option>');
                    });
                    $('#city').prop('disabled', false);
                }
            });
        }
    });

    // City change
    $('#city').on('change', function () {
        var cities_id = $(this).val();
        $('#district, #subdistrict').html('<option value="" disabled selected>Pilih</option>').prop('disabled', true);

        if (cities_id) {
            $.get('/districts/' + cities_id, function (data) {
                $.each(data, function (key, district) {
                    $('#district').append('<option value="' + district.id + '">' + district.dis_name + '</option>');
                });
                $('#district').prop('disabled', false);
            });
        }
    });

    // District change
    $('#district').on('change', function () {
        var district_id = $(this).val();
        $('#subdistrict').html('<option value="" disabled selected>Pilih</option>').prop('disabled', true);

        if (district_id) {
            $.get('/subdistricts/' + district_id, function (data) {
                $.each(data, function (key, subdistrict) {
                    $('#subdistrict').append('<option value="' + subdistrict.id + '">' + subdistrict.subdis_name + '</option>');
                });
                $('#subdistrict').prop('disabled', false);
            });
        }
    });
});

// Facilities Logic
document.addEventListener('DOMContentLoaded', function() {
    const facilityInput = document.getElementById('facilityInput');
    const facilityDropdown = document.getElementById('facilityDropdown');
    const selectedFacilities = document.getElementById('selectedFacilities');
    const hiddenFacilitiesContainer = document.getElementById('hiddenFacilitiesContainer');
    
    let allFacilities = []; // Menyimpan semua data fasilitas
    let selectedIds = []; // Menyimpan ID fasilitas yang dipilih

    // Fungsi untuk memperbarui input tersembunyi dengan ID fasilitas yang dipilih
    function updateFacilitiesInput() {
        // Hapus input fasilitas lama (jika ada)
        hiddenFacilitiesContainer.innerHTML = '';

        // Tambahkan fasilitas yang dipilih ke input tersembunyi
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'facilities[]'; // Pastikan menjadi array
            input.value = id;
            hiddenFacilitiesContainer.appendChild(input);
        });
    }

    // Fungsi untuk menampilkan fasilitas yang dipilih
    function updateFacilitiesDisplay() {
        const selectedFacilityData = allFacilities.filter(facility =>
            selectedIds.includes(facility.id.toString())
        );
        
        selectedFacilities.innerHTML = selectedFacilityData.map(facility => `
            <span class="facility-tag d-flex align-items-center" data-id="${facility.id}">
            <i class="bi ${facility.icon} me-2"></i> <!-- Tambahkan ikon -->
            ${facility.name}
            <span class="remove-facility ms-2">×</span>
            </span>
        `).join('');
        
        updateFacilitiesInput();
    }
    
    // Fungsi untuk mendapatkan data fasilitas dari server
    function fetchFacilities(query) {
        fetch(`/get-facilities?search=${query}`)
            .then(response => response.json())
            .then(data => {
                allFacilities = data; // Simpan semua fasilitas
                const filteredFacilities = data.filter(facility =>
                    facility.name.toLowerCase().includes(query.toLowerCase())
                );
                showFacilitySuggestions(filteredFacilities);
            })
            .catch(error => {
                console.error('Error fetching facilities:', error);
            });
    }

    // Fungsi untuk menampilkan dropdown fasilitas yang cocok
    function showFacilitySuggestions(facilities) {
    facilityDropdown.innerHTML = facilities.map(facility => `
        <div class="dropdown-item d-flex align-items-center" data-id="${facility.id}" data-name="${facility.name}">
        <i class="bi ${facility.icon} me-2"></i> <!-- Tambahkan ikon -->
        ${facility.name}
        </div>
    `).join('');
    facilityDropdown.style.display = facilities.length > 0 ? 'block' : 'none';
    }
    
    // Event listener untuk menangani pencarian fasilitas
    facilityInput.addEventListener('input', function() {
        const query = facilityInput.value.trim();
        if (query.length > 0) {
            fetchFacilities(query);
        } else {
            facilityDropdown.style.display = 'none';
        }
    });
    
    // Event listener untuk memilih fasilitas dari dropdown
    facilityDropdown.addEventListener('click', function(e) {
        if (e.target.classList.contains('dropdown-item')) {
            const id = e.target.getAttribute('data-id');
            const name = e.target.getAttribute('data-name');
            
            // Jika ID fasilitas belum dipilih, tambahkan ke daftar terpilih
            if (!selectedIds.includes(id)) {
                selectedIds.push(id);
                updateFacilitiesDisplay();
            }

            facilityInput.value = ''; // Kosongkan input setelah memilih
            facilityDropdown.style.display = 'none'; // Sembunyikan dropdown
        }
    });
    
    // Event listener untuk menghapus fasilitas yang dipilih
    selectedFacilities.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-facility')) {
            const facilityTag = e.target.closest('.facility-tag');
            const id = facilityTag.getAttribute('data-id');
            selectedIds = selectedIds.filter(selectedId => selectedId !== id);
            updateFacilitiesDisplay();
        }
    });
    
    // Form submission menggunakan AJAX
    const form = document.getElementById('yourFormId'); // Gantilah dengan ID form Anda
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);

        // Hapus data fasilitas lama (jika ada)
        formData.delete('facilities[]'); 

        // Tambahkan fasilitas terpilih
        selectedIds.forEach(id => {
            formData.append('facilities[]', id);  // Pastikan ID dikirim sebagai array
        });

        fetch(form.action, {
            method: form.method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                console.error('Validation Errors:', data.errors);
                alert('Ada error di form. Cek console.');
            } else if (data.message) {
                alert(data.message);
                window.location.href = '{{ route('owner.property') }}'; // Redirect setelah berhasil
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
        });
    });
});



// Image Upload Logic
// document.addEventListener("DOMContentLoaded", function () {
//     const imageList = document.getElementById("image-list");
//     const addImageBtn = document.getElementById("add-image");

//     addImageBtn.addEventListener("click", function () {
//         const newImageField = document.createElement("div");
//         newImageField.classList.add("input-group", "mb-2");
//         newImageField.innerHTML = `
//             <input type="file" name="facilities[]" class="form-control" accept="image/*" required>
//             <button type="button" class="btn btn-danger remove-image">
//                 <span class="material-icons-outlined">delete</span>
//             </button>
//         `;
//         imageList.appendChild(newImageField);
//     });

//     imageList.addEventListener("click", function (e) {
//         if (e.target.closest(".remove-image")) {
//             e.target.closest(".input-group").remove();
//         }
//     });
// });

// Map Logic
document.addEventListener("DOMContentLoaded", function() {
    let map = L.map('map').setView([-2.5489, 118.0149], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
    let marker = L.marker([-2.5489, 118.0149], { draggable: true }).addTo(map);
    
    marker.on('moveend', function (e) {
        const { lat, lng } = e.target.getLatLng();
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
    });
    
    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
    });
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            map.setView([lat, lng], 16);
            marker.setLatLng([lat, lng]);
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        });
    }
});
</script>
@endsection
