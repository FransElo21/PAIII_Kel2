@extends('layouts.index-welcome')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
    .property-scroll {
        max-height: 600px;
        overflow-y: auto;
        padding-right: 10px;
    }
    @media (max-width: 991.98px) {
        .property-scroll {
            max-height: none;
            overflow: visible;
            padding-right: 0;
        }
        #map {
            min-height: 300px;
            margin-top: 1.5rem;
        }
    }
    #map {
        width: 100%;
        height: 600px;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        background: #f5f6fa;
    }
    .property-card {
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #eaeaea;
        background-color: #fff;
        cursor: pointer;
        text-decoration: none !important;
        color: inherit;
        display: block;
    }
    .property-card:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 8px 20px rgba(40,154,132,0.09);
        text-decoration: none;
    }
    .property-card .card-img-top {
        height: 130px;
        object-fit: cover;
    }
    .no-results {
        background: #f8fafc;
        border-radius: 1rem;
        padding: 3rem 1.5rem;
        text-align: center;
    }
    /* Breadcrumb */
    .breadcrumb {
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
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
    /* Search Form */
    .search-input-group {
        max-width: 70%;
        margin: 0 auto 2rem;
    }
    .search-input-group .form-control {
        border-radius: 50px 0 0 50px;
        border-right: none;
        padding-left: 1.5rem;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .search-input-group .btn {
        border-radius: 0 50px 50px 0;
        background: linear-gradient(135deg, #289A84, #38a169);
        color: white;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s ease;
    }
    .search-input-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(40, 154, 132, 0.4);
    }
</style>

<div class="container pt-3">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('landingpage') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pencarian</li>
    </ol>

    <!-- Search Form -->
    <div class="search-input-group mx-auto mb-3">
        <form action="{{ route('search_welcomeProperty') }}" method="GET" class="d-flex">
            <input type="text"
                   name="keyword"
                   class="form-control"
                   value="{{ request('keyword') }}"
                   placeholder="Cari lokasi, area, atau alamat...">
            <button class="btn" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="row">
        <!-- KIRI: DAFTAR PROPERTY -->
        <div class="col-lg-7 mb-4 mb-lg-0">
            @if($paginatedProperties->isEmpty())
                <div class="no-results">
                    <i class="fas fa-home fa-3x"></i>
                    <h5 class="mt-3 mb-1 text-gray-600">Tidak ada properti ditemukan</h5>
                    <p class="text-gray-500">Coba kata kunci lain atau ubah filter pencarian.</p>
                </div>
            @else
                <div class="property-scroll">
                    @foreach ($paginatedProperties as $property)
                        <a href="{{ route('detail-property.show', $property->property_id) }}" class="text-decoration-none">
                        <div class="card mb-4 shadow-sm property-card">
                            <div class="row g-0 align-items-center">
                                <!-- FOTO UTAMA -->
                                <div class="col-4 col-md-4">
                                    <div class="position-relative h-100">
                                        <img src="{{ asset('storage/' . $property->image) }}"
                                             class="img-fluid card-img-top w-100 h-100"
                                             alt="{{ $property->property_name }}">
                                        <span class="position-absolute top-0 start-0 m-2 px-2 py-1 bg-success text-white rounded-2 small shadow-sm" style="font-size:13px;">
                                            {{ $property->property_type }}
                                        </span>
                                    </div>
                                </div>
                                <!-- INFO PROPERTY -->
                                <div class="col-8 col-md-8">
                                    <div class="card-body py-2 px-3 d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="fw-bold text-dark" style="font-size:1.08rem;">{{ $property->property_name }}</span>
                                            </div>
                                            <div class="mb-1 text-muted" style="font-size: 14px;">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $property->subdistrict }}, {{ $property->city }}
                                            </div>
                                            <!-- Rating -->
                                            <div class="mb-1 d-flex align-items-center" style="font-size:1rem;">
                                                @php
                                                    $avgRating = round($property->avg_rating, 1);
                                                    $fullStars = floor($avgRating);
                                                    $halfStar = ($avgRating - $fullStars) >= 0.5 ? true : false;
                                                @endphp
                                                <span class="fw-bold text-dark me-1">{{ $avgRating }}</span>
                                                @for ($i = 1; $i <= $fullStars; $i++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                                @if ($halfStar)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @endif
                                                @for ($i = $fullStars + $halfStar + 1; $i <= 5; $i++)
                                                    <i class="far fa-star text-warning"></i>
                                                @endfor
                                                <span class="ms-2 text-secondary" style="font-size:0.95rem;">
                                                    ({{ $property->total_reviews ?? 0 }}) <span class="ms-1">Ulasan</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-danger fs-5 mb-0">
                                                IDR {{ number_format($property->min_price) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    @endforeach
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $paginatedProperties->links() }}
                </div>
            @endif
        </div>
        <!-- KANAN: MAP -->
        <div class="col-lg-5">
            <div id="map"></div>
        </div>
    </div>
</div>

<!-- LEAFLET MAP SCRIPT -->
<script>
    const properties = [
        @foreach($paginatedProperties as $property)
        {
            name: "{{ addslashes($property->property_name) }}",
            lat: {{ $property->latitude ?? 0 }},
            lng: {{ $property->longitude ?? 0 }},
            address: "{{ addslashes($property->subdistrict) }}",
            image: "{{ asset('storage/' . $property->image) }}"
        },
        @endforeach
    ];

    // Fallback jika tidak ada properti
    let firstLat = properties.length > 0 && properties[0].lat ? properties[0].lat : -2.5489;
    let firstLng = properties.length > 0 && properties[0].lng ? properties[0].lng : 118.0149;

    var map = L.map('map').setView([firstLat, firstLng], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    properties.forEach(function(property) {
        if(property.lat && property.lng){
            var marker = L.marker([property.lat, property.lng]).addTo(map);
            marker.bindPopup(`
                <div style="min-width:170px">
                    <img src="${property.image}" alt="${property.name}" style="width:100%; height:80px; object-fit:cover; border-radius:8px; margin-bottom:6px;">
                    <strong>${property.name}</strong><br>
                    <small>${property.address}</small>
                </div>
            `);
        }
    });
</script>

@endsection
