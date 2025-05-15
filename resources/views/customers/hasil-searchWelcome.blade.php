@extends('layouts.index-welcome')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
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

    /* Search Form */
    .search-input-group {
        max-width: 600px;
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

    /* Property Cards */
    .property-card {
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #ffffff;
        border: none;
    }

    .property-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .property-card .card-img-top {
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .property-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .property-card .card-body {
        padding: 1rem;
    }

    .property-card h6 {
        color: #1e293b;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .property-card .text-danger {
        font-weight: 700;
        font-size: 1rem;
    }

    /* No Results */
    .no-results {
        background: #f8fafc;
        border-radius: 1rem;
        padding: 3rem 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .no-results i {
        color: #cbd5e1;
        font-size: 3rem;
        transition: transform 0.3s ease;
    }

    .no-results:hover i {
        transform: rotate(10deg);
    }

    /* Pagination */
    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }

    .page-link {
        color: #289A84;
        border: none;
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background-color: #e6f7f1;
        color: #152C5B;
    }

    .page-item.active .page-link {
        background-color: #289A84;
        color: white;
        box-shadow: 0 4px 12px rgba(40, 154, 132, 0.3);
    }
</style>

<div class="container">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('landingpage') }}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pencarian</li>
    </ol>

    <!-- Search Form -->
    <div class="search-input-group mx-auto">
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

    <!-- Search Keyword -->
    @if(request('keyword'))
        <div class="text-center my-4">
            <h5 class="text-muted">Mencari: <strong>"{{ request('keyword') }}"</strong></h5>
        </div>
    @endif

    <!-- Results Grid -->
    @if($paginatedProperties->isEmpty())
        <!-- No Results -->
        <div class="no-results">
            <i class="fas fa-home fa-3x"></i>
            <h5 class="mt-3 mb-1 text-gray-600">Tidak ada properti ditemukan</h5>
            <p class="text-gray-500">Coba kata kunci lain atau ubah filter pencarian.</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-4 g-4">
            @foreach ($paginatedProperties as $property)
                <div class="col fade-in">
                    <a href="{{ route('detail-property.show', $property->property_id) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden property-card position-relative transition-card">
                            <!-- Badge Tipe Properti -->
                            <span class="position-absolute top-0 start-0 m-2 px-3 py-1 bg-success text-white rounded-pill small shadow-sm">
                                {{ $property->property_type }}
                            </span>
                            
                            <!-- Gambar Properti -->
                            <div class="overflow-hidden" style="aspect-ratio: 3/2;">
                                <img src="{{ asset('storage/' . $property->image) }}"
                                    class="card-img-top object-fit-cover"
                                    alt="{{ $property->property_name }}">
                            </div>
                            
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="card-title mb-1 fw-bold text-dark">{{ $property->property_name }}</h6>
                                    <small class="text-muted mb-2">{{ $property->subdistrict }}, Surabaya</small>
                                    
                                    <!-- Rating Section -->
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="me-2">
                                            @php
                                                $avgRating = round($property->avg_rating, 1);
                                                $fullStars = floor($avgRating);
                                                $halfStar = ($avgRating - $fullStars) >= 0.5 ? true : false;
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
                                        
                                        <span class="text-muted small">
                                            {{ $property->total_reviews > 0 ? number_format($avgRating, 1) . ' (' . $property->total_reviews . ')' : 'Belum ada ulasan' }}
                                        </span>
                                    </div>
                                </div>
        
                                <div>
                                    <p class="mb-1">
                                        <span class="text-danger fw-bold fs-5">
                                            IDR {{ number_format($property->min_price) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $paginatedProperties->links() }}
        </div>
    @endif
</div>
@endsection