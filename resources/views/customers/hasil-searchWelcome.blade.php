@extends('layouts.index-welcome')

@section('content')

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
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($paginatedProperties as $property)
                <div class="col">
                    <a href="{{ route('detail-property.show', $property->property_id) }}" class="text-decoration-none">
                        <div class="card h-100 property-card">
                            <!-- Image -->
                            <img src="{{ asset('storage/' . $property->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $property->property_name }}">
                            
                            <!-- Card Body -->
                            <div class="card-body">
                                <h6 class="card-title">{{ $property->property_name }}</h6>
                                <p class="text-muted mb-1">{{ $property->city }}</p>
                                <p class="mb-0 text-success fw-bold">Rp {{ number_format($property->min_price, 0, ',', '.') }}</p>
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