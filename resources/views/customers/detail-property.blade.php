@extends('layouts.index-welcome')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Di bagian atas file Blade -->
@php
    $propertyId = $property->property_id; // atau sesuai sumber data Anda
    $userId = auth()->id();       // jika user sudah login
@endphp

@if (session('error'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <style>
        :root {
            --primary: #6C63FF;
            --secondary: #4D44DB;
            --accent: #FF6584;
            --dark: #2E2E2E;
            --light: #F8F9FA;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
        }
        
        .property-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }
        
        .gallery-main {
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .gallery-thumb {
            height: 180px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .gallery-thumb:hover {
            transform: scale(1.03);
        }
        
        .see-all-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(255,255,255,0.9) !important;
        }
        
        .facility-item {
            margin-bottom: 8px;
        }
        
        .room-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .booking-card {
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 20px;
        }
        
        .price-tag {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        #map {
            border-radius: 12px;
            height: 400px;
        }
        .custom-radius {
            border-radius: 30px;
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
            html {
                scroll-behavior: smooth;
            }
    </style>
    
    <div class="container">
        <!-- Header Section -->    
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('landingpage') }}">Beranda</a></li>
          <li class="breadcrumb-item"><a href="#">Property</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $property->property_name }}</li>
        </ol>
        
        <!-- Gallery Section -->
        <div class="row g-3 fade-in">
            @php
                $imagesCollection = collect($images);
                $firstFiveImages = $imagesCollection->take(5);
            @endphp
        
            <div class="row g-2 mb-4">
                {{-- Gambar utama kiri --}}
                <div class="col-md-6">
                    @if(isset($firstFiveImages[0]))
                        <div class="rounded-4 overflow-hidden shadow-sm" style="height: 100%; min-height: 390px;">
                            <img src="{{ asset('storage/' . $firstFiveImages[0]->images_path) }}" class="img-fluid w-100 h-100 object-fit-cover rounded-4" alt="Main Photo">
                        </div>
                    @endif
                </div>
        
                {{-- Grid gambar kanan --}}
                <div class="col-md-6 d-grid gap-2" style="grid-template-columns: repeat(2, 1fr); display: grid;">
                    @foreach($firstFiveImages->slice(1)->values() as $index => $img)
                        <div class="overflow-hidden rounded-4 shadow-sm position-relative" style="height: 190px;">
                            <img src="{{ asset('storage/' . $img->images_path) }}" class="img-fluid w-100 h-100 object-fit-cover rounded-4" alt="Image {{ $index + 2 }}">
                            @if($index === 3 && $imagesCollection->count() > 5)
                                <button class="btn btn-light see-all-btn shadow-sm position-absolute bottom-0 end-0 m-2" data-bs-toggle="modal" data-bs-target="#photoModal" style="border-radius: 20px;">
                                    <i class="bi bi-images me-1"></i> Lihat semua
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
             
        <div class="row">
            <div class="property-header fade-in d-flex justify-content-between align-items-start flex-wrap">
                <!-- Kolom Kiri -->
                <div class="me-3">
                     
                    <!-- Nama Properti -->
                    <h2 class="fw-bold mb-1">{{ $property->property_name }}</h2>
            
                    <!-- Lokasi -->
                    <div class="d-flex align-items-center gap-2 text-muted mb-2 p-2 rounded-3 hover-effect">
                        <span class="location-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <span class="location-text">{{ $locationData->subdis_name }}, {{ $locationData->city_name }}</span>
                    </div>  

                    <!-- Rating -->
                    <div class="d-flex align-items-center">
                        @if($totalReviews > 0)
                            <span class="fw-bold fs-5 me-1 text-dark"><i class="fas fa-star text-warning me-1" style="font-size: 1.2rem;"></i>{{ number_format($avgRating, 1) }}</span>
                            <span class="text-muted">({{ $totalReviews }} ulasan)</span>
                        @endif
                    </div>
                </div>
            
                <!-- Kolom Kanan -->
                <div class="text-end ms-auto">
                    <div class="text-muted">Mulai dari</div>
                    <div class="text-danger fw-bold fs-4">
                        IDR {{ number_format(
                            $property_roomPrice[0]->min_price ?? 236128, 
                            0, ',', '.'
                        ) }}
                    </div>
                    <div class="text-muted small">/kamar/malam</div>
                    <a href="#sticky-date-picker" class="btn btn-primary mt-2" style="border-radius: 20px;">Lihat kamar</a>
                </div>
            </div>            
            <hr>
            <!-- Main Content -->
            <div class="col-lg-12">
                <!-- Description -->
                <div class="fade-in mb-5">
                    <h4 class="fw-bold">Deskripsi</h4>
                    <p class="fs-7">{{ $property->description ?? 'Deskripsi belum ditambahkan.' }}</p>
                </div>
                <hr>
                <!-- Facilities -->
                <div class=" fade-in">
                    <h4 class="fw-bold mb-3">Fasilitas Umum</h4>
                    @if(!empty($fasilitas))
                        <ul class="list-unstyled">
                            @foreach($fasilitas as $f)
                            <li class="d-flex align-items-center mb-2 fs-6">
                                <i class="bi {{ $f->icon }} me-2" style="font-size: 1.5rem;"></i>
                                <span>{{ $f->facility_name }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Fasilitas belum tersedia</p>
                    @endif
                </div>
                <hr>

                <style>
                    /* CSS untuk sticky card */
                    #sticky-date-picker {
                        position: sticky;
                        top: 70px; /* Sesuaikan dengan tinggi header */
                        z-index: 999;
                        transition: box-shadow 0.3s ease;
                    }

                    #sticky-date-picker.is-sticky {
                        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                        background: white !important;
                    }

                    @media (max-width: 768px) {
                        #sticky-date-picker {
                            position: relative;
                            top: auto;
                        }
                    }
                </style>

                <!-- Check-in & Check-out -->
                <div id="sticky-date-picker" class="mb-5 fade-in">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4">
            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
                <i class="bi bi-calendar-event me-2 text-success"></i> Pilih Tanggal Menginap
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @if($property->property_type_id == 1)
                    <!-- Homestay -->
                    <div class="col-md-6">
                        <label for="checkInDate" class="form-label fw-medium text-muted">Check-in</label>
                        <input type="text" id="checkInDate" class="form-control" placeholder="Pilih tanggal masuk" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="checkOutDate" class="form-label fw-medium text-muted">Check-out</label>
                        <input type="text" id="checkOutDate" class="form-control" placeholder="Pilih tanggal keluar" readonly>
                    </div>
                @else
                    <!-- Kost -->
                    <div class="col-md-6">
                        <label for="startDate" class="form-label fw-medium text-muted">Tanggal Masuk</label>
                        <input type="text" id="startDate" class="form-control" placeholder="Pilih tanggal masuk" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="duration" class="form-label fw-medium text-muted">Durasi Sewa</label>
                        <select id="duration" class="form-select">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ $i }} Bulan</option>
                            @endfor
                        </select>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


                <script>
                    const stickyCard = document.getElementById('sticky-date-picker');

                    if (stickyCard) {
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (!entry.isIntersecting) {
                                    stickyCard.classList.add('is-sticky');
                                } else {
                                    stickyCard.classList.remove('is-sticky');
                                }
                            });
                        }, {
                            threshold: 0.1,
                            rootMargin: "-80px 0px 0px 0px" // Sesuaikan dengan tinggi header
                        });

                        observer.observe(stickyCard);
                    }
                </script>

                <!-- Available Rooms -->
                <div id="available-rooms" class="mb-5 fade-in">
                    <h4 class="fw-bold mb-3">Tipe Kamar Tersedia</h4>
                    <div class="row g-3">
                        @if(empty($rooms))
                            <p class="text-muted">Tidak ada kamar yang tersedia.</p>
                        @else
                        @foreach($rooms as $room)
                            <div class="card mb-4 shadow-sm border-0 rounded overflow-hidden">
                                <div class="row g-0 h-100">

                                    <!-- Kolom Gambar -->
                                    <div class="col-md-4 position-relative">
                                        @if($room->image_path)
                                            <img src="{{ asset('storage/' . $room->image_path) }}" 
                                                class="img-fluid h-100 w-100" 
                                                style="object-fit: cover;" 
                                                alt="{{ $room->room_type }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif

                                        <!-- Overlay Info -->
                                        <div class="position-absolute bottom-0 start-0 w-100 p-2 bg-light bg-opacity-90">
                                            <div class="d-flex align-items-center gap-2 small text-muted">
                                                <i class="bi bi-door-open-fill text-primary"></i>
                                                <span>{{ $room->luas ?? '18m²' }}</span>
                                                <i class="bi bi-person-fill ms-2 text-primary"></i>
                                                <span>{{ $room->kapasitas ?? '2 Guests' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kolom Informasi -->
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold">{{ $room->room_type }}</h5>
                                            <hr class="my-2">

                                            <!-- Fasilitas -->
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-6 d-flex align-items-center gap-2">
                                                    <i class="bi bi-person text-primary"></i>
                                                    <small>2 Tamu</small>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-center gap-2">
                                                    <i class="bi bi-car-front text-primary"></i>
                                                    <small>Double</small>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-center gap-2">
                                                    <i class="bi bi-cup-hot text-primary"></i>
                                                    <small>Sarapan tidak tersedia</small>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-center gap-2">
                                                    <i class="bi bi-wifi text-primary"></i>
                                                    <small>Wi-Fi Gratis</small>
                                                </div>
                                            </div>

                                            <!-- Harga -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-danger bg-opacity-10 text-danger fs-6">Sisa {{ $room->stok }} kamar lagi!</span>
                                                <div class="text-end">
                                                    <div class="fs-5 text-danger fw-bold">Rp{{ number_format($room->latest_price, 0, ',', '.') }}</div>
                                                    <small class="text-muted">/kamar/malam</small>
                                                </div>
                                            </div>

                                            <!-- Tombol Pesan Awal -->
                                            <div class="d-flex justify-content-end" id="btn-area-{{ $room->room_id }}">
                                                <button class="btn btn-primary px-4 py-2 fw-semibold" style="border-radius: 20px;"
                                                    onclick="showQuantityControls({{ $room->room_id }}, {{ $room->latest_price }})">
                                                    Pilih
                                                </button>
                                            </div>

                                            <!-- Input Jumlah Kamar (Awalnya disembunyikan) -->
                                            <div id="quantity-area-{{ $room->room_id }}" class="d-none mt-3">
                                                <div class="d-flex justify-content-end gap-2 align-items-center mb-2">
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                            onclick="decreaseQuantity({{ $room->room_id }})">-</button>
                                                            <input type="number"
                                                                class="quantity-input form-control form-control-sm text-center"
                                                                value="0"
                                                                min="0"
                                                                max="{{ $room->stok }}"
                                                                id="quantity-input-{{ $room->room_id }}"
                                                                data-room-id="{{ $room->room_id }}"
                                                                data-price="{{ $room->latest_price }}"
                                                                onchange="updateTotalPrice()">
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                            onclick="increaseQuantity({{ $room->room_id }})">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @endif
                    </div>
                </div>  
                
                <!-- Footer Total Harga -->
                <div class="position-fixed bottom-0 start-0 w-100 bg-white py-3 shadow-sm border-top" id="total-footer" style="z-index: 1000; display: none;">
                    <div class="container">
                        <!-- Ringkasan Kamar -->
                        <div id="selected-rooms-summary" class="mb-2"></div>

                        <!-- Total Harga -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Total</div>
                                <h5 class="text-danger fw-bold fs-5 mb-0" id="total-harga-footer">Rp0</h5>
                            </div>
                            <button class="btn btn-primary px-4" style="border-radius: 20px;" onclick="prosesPemesanan()">
                                Pesan
                            </button>
                        </div>
                    </div>
                </div>

                <style>
                    .custom-swal-popup {
                        border-bottom-left-radius: 15px;
                        border-bottom-right-radius: 15px;
                    }
                    .position-fixed.bottom-0.start-0.w-100.bg-white.py-3.shadow-sm.border-top {
                        z-index: 1000; /* Pastikan footer selalu di atas */
                        background-color: white;
                        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05); /* Efek bayangan */
                    }
                    .quantity-controls {
                        display: inline-flex;
                        align-items: center;
                    }
                    .quantity-controls button {
                        background-color: #e0e0e0;
                        color: #333;
                        border: none;
                        padding: 5px 10px;
                        font-size: 1rem;
                        cursor: pointer;
                        border-radius: 50%;
                    }
                    .quantity-controls button:hover {
                        background-color: #ccc;
                    }
                    .quantity-input {
                        width: 40px;
                        text-align: center;
                        margin: 0 5px;
                        border: 1px solid #ddd;
                        padding: 5px;
                        font-size: 1rem;
                    }
                    .total-price {
                        font-size: 1rem;
                        color: #dc3545;
                        font-weight: bold;
                    }
                    .btn-primary-alt {
                        background-color: #fd7e14;
                        border: none;
                        color: white;
                    }
                    .btn-primary-alt:hover {
                        background-color: #e06f0c;
                    }
                    /* Animasi Hover untuk Card */
                    .card.transform-hover:hover {
                        transform: translateY(-5px);
                        transition: transform 0.3s ease;
                    }

                    .rating-stars {
                        display: flex;
                        gap: 2px;
                        font-size: 1.2rem;
                    }

                    .rating-stars input[type="radio"] {
                        display: none;
                    }

                    .rating-stars label:hover,
                    .rating-stars label:hover ~ label {
                        color: #facc15 !important;
                        transition: color 0.2s ease;
                    }

                    .rating-stars input[type="radio"]:checked ~ label {
                        color: #facc15 !important;
                    }
                </style>

                <hr>                    
                <!-- Location Map -->
                <div class="mb-5 fade-in">
                    <h4 class="fw-bold mb-3">Lokasi</h4>
                    @if($property->latitude && $property->longitude)
                        <div id="map" style="height: 400px;" class="rounded shadow-sm mb-4"></div>
                    @endif
                </div>

                <style>
                    .map-section {
                        position: relative;
                        width: 100%;
                        height: 500px; /* Sesuaikan tinggi map */
                        overflow: hidden;
                        z-index: 1; /* Lebih rendah dari header */
                    }

                    .map-canvas {
                        width: 100%;
                        height: 100%;
                        border-radius: 12px;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    }
                </style>
                
                <!-- Reviews -->
                <div class="mb-5 fade-in">
                    <h4 class="fw-bold mb-4">Ulasan</h4>
                    <div class="d-flex align-items-center">
                        @if($totalReviews > 0)
                            <span class="fw-bold fs-5 me-1 text-dark"><i class="fas fa-star text-warning me-1" style="font-size: 1.2rem;"></i>{{ number_format($avgRating, 1) }}</span>
                            <span class="text-muted">({{ $totalReviews }} ulasan)</span>
                        @endif
                    </div>

                    <!-- Daftar Ulasan -->
                    @if(!empty($reviews))
                        <div class="row g-4">
                            @foreach($reviews as $review)
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transform-hover">
                                        <div class="card-body p-4">
                                            <!-- Header Review -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark">{{ $review->user_name }}</strong>
                                                        <small class="text-muted d-block">
                                                            {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="rating-stars">
                                                    @php
                                                        $rating = $review->rating;
                                                        $fullStars = floor($rating);
                                                        $halfStar = ($rating - $fullStars) >= 0.5 ? true : false;
                                                    @endphp
                
                                                    <!-- Bintang Penuh -->
                                                    @for ($i = 1; $i <= $fullStars; $i++)
                                                        <i class="fas fa-star text-warning me-1"></i>
                                                    @endfor
                
                                                    <!-- Bintang Setengah -->
                                                    @if ($halfStar)
                                                        <i class="fas fa-star-half-alt text-warning me-1"></i>
                                                        @php $fullStars++ @endphp
                                                    @endif
                
                                                    <!-- Bintang Kosong -->
                                                    @for ($i = $fullStars + 1; $i <= 5; $i++)
                                                        <i class="far fa-star text-warning me-1"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            
                                            <!-- Konten Ulasan -->
                                            <p class="card-text text-muted mb-0">
                                                {{ $review->comment }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 bg-light rounded-4 mt-3">
                            <i class="fas fa-comment-dots text-secondary" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted fw-normal">Belum ada ulasan</h5>
                            <p class="text-muted">Jadilah yang pertama memberikan ulasan untuk properti ini.</p>
                        </div>
                    @endif
                
                    <!-- Tombol Lihat Semua Ulasan (Opsional) -->
                    @if($totalReviews > 3)
                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#allReviewsModal">
                                <i class="fas fa-eye me-2"></i>Lihat Semua Ulasan
                            </button>
                        </div>
                    @endif
                </div>
            </div> 
        </div>
    </div>
    
    {{-- Modal Foto --}}
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Semua Foto {{ $property->property_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @foreach($images as $img)
                            @if(isset($img->images_path))
                                <div class="col-md-4 mb-3">
                                    <img src="{{ asset('storage/' . $img->images_path) }}" class="img-fluid rounded" alt="Foto Properti">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function updateHiddenRoomInputs() {
            @foreach($rooms as $room)
                const jumlahInput = document.getElementById('jumlah_{{ $room->room_id }}');
                const hiddenInput = document.getElementById('hidden_jumlah_{{ $room->room_id }}');
                hiddenInput.value = jumlahInput.value;
            @endforeach
        }
    </script>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        const typeId = {{ $property->property_type_id }};
    
        document.addEventListener('DOMContentLoaded', () => {
            if (typeId === 1) {
                // Homestay
                const start = document.getElementById('startDate');
                const end = document.getElementById('endDate');
                [start, end].forEach(input => input.addEventListener('change', hitungHomestay));
            } else if (typeId === 2) {
                // Kost
                const durasi = document.getElementById('duration');
                durasi.addEventListener('input', hitungKost);
            }
        });
    
        function hitungHomestay() {
            const start = new Date(document.getElementById('startDate').value);
            const end = new Date(document.getElementById('endDate').value);
            const totalText = document.getElementById('totalHarga');
    
            if (start && end && end > start) {
                const diffDays = Math.round((end - start) / (1000 * 60 * 60 * 24));
                const total = diffDays * price;
                totalText.textContent = `Total untuk ${diffDays} malam: Rp ${total.toLocaleString('id-ID')}`;
            } else {
                totalText.textContent = '';
            }
        }
    
        function hitungKost() {
            const durasi = parseInt(document.getElementById('duration').value);
            const totalText = document.getElementById('totalHarga');
    
            if (!isNaN(durasi) && durasi > 0) {
                const total = durasi * price;
                totalText.textContent = `Total untuk ${durasi} bulan: Rp ${total.toLocaleString('id-ID')}`;
            } else {
                totalText.textContent = '';
            }
        }
    </script>    
        <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("#start-kos", {
            dateFormat: "d/m/Y",
            minDate: "today",
            defaultDate: "today",
        });
    </script>
    <script>
        const durationSelector = document.getElementById('duration-kos');
        durationSelector.addEventListener('change', () => {
            const months = parseInt(durationSelector.value);
            console.log(`Sewa selama ${months} bulan`);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const latitude = {{ $property->latitude ?? 0 }};
            const longitude = {{ $property->longitude ?? 0 }};

            if (latitude && longitude) {
                const map = L.map('map').setView([latitude, longitude], 15);

                // Tambahkan layer peta dari OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
                }).addTo(map);

                // Tambahkan marker di posisi properti
                L.marker([latitude, longitude])
                    .addTo(map)
                    .bindPopup("{{ $property->property_name }}")
                    .openPopup();
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach(element => {
                element.classList.add('visible');
            });
        });
    </script>

<script>
    // Variabel global
    let diffDays = 0;

    function showQuantityControls(roomId, pricePerRoom) {
        const userId = "{{ $userId }}";

        if (!userId) {
            Swal.fire({
                title: 'Login Diperlukan',
                text: 'Anda harus login terlebih dahulu untuk memesan kamar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#007BFF',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'custom-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
            return;
        }

        const typeId = {{ $property->property_type_id }};
        let isValid = false;

        if (typeId === 1) {
            const checkIn = document.getElementById("checkInDate").value;
            const checkOut = document.getElementById("checkOutDate").value;
            if (checkIn && checkOut) isValid = true;
        } else {
            const startDate = document.getElementById("startDate").value;
            const duration = document.getElementById("duration").value;
            if (startDate && duration) isValid = true;
        }

        if (!isValid) {
            alert("Silakan lengkapi tanggal pemesanan terlebih dahulu.");
            return;
        }

        const quantityArea = document.getElementById(`quantity-area-${roomId}`);
        const btnArea = document.getElementById(`btn-area-${roomId}`);

        quantityArea.classList.remove("d-none");
        if (btnArea) btnArea.classList.add("d-none");

        const input = document.getElementById(`quantity-input-${roomId}`);
        if (input) {
            input.value = 1;
            updateTotalPrice();
        }
    }

    function increaseQuantity(roomId) {
        const input = document.getElementById(`quantity-input-${roomId}`);
        const max = parseInt(input.max);
        let val = parseInt(input.value);

        if (!isNaN(max) && val < max) {
            input.value = val + 1;
            updateTotalPrice();
        }
    }

    function decreaseQuantity(roomId) {
        const input = document.getElementById(`quantity-input-${roomId}`);
        let val = parseInt(input.value);

        if (!isNaN(val) && val > 0) {
            input.value = val - 1;
            updateTotalPrice();

            // Jika jumlah kembali ke 0, sembunyikan form dan tampilkan tombol pesan
            if (val - 1 === 0) {
                document.getElementById(`quantity-area-${roomId}`).classList.add("d-none");
                const btnArea = document.getElementById(`btn-area-${roomId}`);
                if (btnArea) btnArea.classList.remove("d-none");
            }
        }
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka);
    }

    function updateTotalPrice() {
        const typeId = {{ $property->property_type_id }};
        let totalPrice = 0;
        const summaryContainer = document.getElementById("selected-rooms-summary");
        summaryContainer.innerHTML = "";
        const fragment = document.createDocumentFragment();

        const inputs = document.querySelectorAll(".quantity-input");

        if (typeId === 1) {
            const checkIn = document.getElementById("checkInDate").value;
            const checkOut = document.getElementById("checkOutDate").value;
            if (!checkIn || !checkOut || checkOut <= checkIn) return;
            const start = new Date(checkIn);
            const end = new Date(checkOut);
            diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

            inputs.forEach(input => {
                const qty = parseInt(input.value);
                const price = parseFloat(input.dataset.price);
                const roomName = input.closest('.card')?.querySelector('.card-title')?.innerText || "Kamar";
                if (qty > 0) {
                    const subtotal = qty * price * diffDays;
                    totalPrice += subtotal;
                    const div = document.createElement("div");
                    div.className = "d-flex justify-content-between";
                    div.innerHTML = `<small>${roomName} x ${qty}</small><small>Rp${subtotal.toLocaleString('id-ID')}</small>`;
                    fragment.appendChild(div);
                }
            });

            if (diffDays > 0) {
                fragment.appendChild(document.createElement("hr"));
                const durasi = document.createElement("div");
                durasi.className = "d-flex justify-content-between text-muted";
                durasi.innerHTML = `<small>Durasi</small><small>${diffDays} malam</small>`;
                fragment.appendChild(durasi);
            }

        } else {
            const startDate = document.getElementById("startDate").value;
            const duration = parseInt(document.getElementById("duration").value);
            if (!startDate || !duration) return;

            inputs.forEach(input => {
                const qty = parseInt(input.value);
                const price = parseFloat(input.dataset.price);
                const roomName = input.closest('.card')?.querySelector('.card-title')?.innerText || "Kamar";
                if (qty > 0) {
                    const subtotal = qty * price * duration;
                    totalPrice += subtotal;
                    const div = document.createElement("div");
                    div.className = "d-flex justify-content-between";
                    div.innerHTML = `<small>${roomName} x ${qty}</small><small>Rp${subtotal.toLocaleString('id-ID')}</small>`;
                    fragment.appendChild(div);
                }
            });

            fragment.appendChild(document.createElement("hr"));
            const durasi = document.createElement("div");
            durasi.className = "d-flex justify-content-between text-muted";
            durasi.innerHTML = `<small>Durasi</small><small>${duration} bulan</small>`;
            fragment.appendChild(durasi);
        }

        summaryContainer.appendChild(fragment);

        const totalDisplay = document.getElementById("total-harga-footer");
        const totalFooter = document.getElementById("total-footer");

        if (totalPrice > 0) {
            totalDisplay.textContent = formatRupiah(totalPrice);
            totalFooter.style.display = "block";
        } else {
            totalFooter.style.display = "none";
        }
    }

    function prosesPemesanan() {
        const typeId = {{ $property->property_type_id }};
        const propertyId = "{{ $propertyId }}";
        const userId = "{{ $userId }}";
        let selectedRooms = [];
        let totalPrice = 0;

        if (typeId === 1) {
            // Homestay
            const checkIn = document.getElementById("checkInDate").value;
            const checkOut = document.getElementById("checkOutDate").value;
            if (!checkIn || !checkOut || checkOut <= checkIn) {
                alert("Isi tanggal check-in dan check-out");
                return;
            }

            document.querySelectorAll(".quantity-input").forEach(input => {
                const qty = parseInt(input.value);
                const price = parseFloat(input.dataset.price);
                const roomId = input.dataset.roomId;
                if (qty > 0) {
                    const subtotal = qty * price * diffDays;
                    totalPrice += subtotal;
                    selectedRooms.push({ room_id: roomId, quantity: qty, price_per_room: price, subtotal });
                }
            });

            if (selectedRooms.length === 0) {
                alert("Pilih kamar terlebih dahulu.");
                return;
            }

            window.location.href = `/pemesanan?rooms=${encodeURIComponent(JSON.stringify(selectedRooms))}&total_price=${totalPrice}&property_id=${propertyId}&user_id=${userId}&check_in=${checkIn}&check_out=${checkOut}`;
        
        } else {
            // Kost
            const startDate = document.getElementById("startDate").value;
            const duration = parseInt(document.getElementById("duration").value);

            if (!startDate || !duration || duration <= 0) {
                alert("Isi tanggal dan durasi kost.");
                return;
            }

            document.querySelectorAll(".quantity-input").forEach(input => {
                const qty = parseInt(input.value);
                const price = parseFloat(input.dataset.price);
                const roomId = input.dataset.roomId;
                if (qty > 0 && roomId) {
                    const subtotal = qty * price * duration;
                    totalPrice += subtotal;
                    selectedRooms.push({ room_id: roomId, quantity: qty, price_per_room: price, subtotal });
                }
            });

            if (selectedRooms.length === 0) {
                alert("Pilih kamar terlebih dahulu.");
                return;
            }

            window.location.href = `/pemesanan-kost?rooms=${encodeURIComponent(JSON.stringify(selectedRooms))}&total_price=${totalPrice}&property_id=${propertyId}&user_id=${userId}&start_date=${startDate}&duration=${duration}`;
        }
    }


    function validateStock() {
        let hasInvalidStock = false;

        document.querySelectorAll(".quantity-input").forEach(input => {
            const maxQty = parseInt(input.max);
            const qty = parseInt(input.value);

            if (qty > maxQty) {
                hasInvalidStock = true;
                input.value = maxQty;
            }
        });

        if (hasInvalidStock) {
            alert("Jumlah kamar melebihi stok! Sudah disesuaikan.");
            updateTotalPrice();
        }
    }
</script>

<script>
    document.querySelector('a[href="#available-rooms"]').addEventListener('click', function(e) {
    e.preventDefault();
    document.querySelector('#available-rooms').scrollIntoView({ behavior: 'smooth' });
});
</script>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.css" />
<script src="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr("#checkInDate", {
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            flatpickr("#checkOutDate", {
                altInput: true,
                altFormat: "d F Y",
                dateFormat: "Y-m-d",
                minDate: dateStr,
                onChange: updateTotalPrice
            });
            updateTotalPrice();
        }
    });

    flatpickr("#checkOutDate", {
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        onChange: updateTotalPrice
    });
</script>

<script>
@if($property->property_type_id == 1)
    flatpickr("#checkInDate", {
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr) {
            flatpickr("#checkOutDate", {
                altInput: true,
                altFormat: "d F Y",
                dateFormat: "Y-m-d",
                minDate: dateStr,
                onChange: updateTotalPrice
            });
            updateTotalPrice();
        }
    });
    flatpickr("#checkOutDate", {
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        onChange: updateTotalPrice
    });
@else
    flatpickr("#startDate", {
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: updateTotalPrice
    });
@endif
</script>


@endsection