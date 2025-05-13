@extends('layouts.index-welcome')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .search-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        margin-top: -3rem;
    }

    .search-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
    }

    .search-field {
        flex: 1;
        min-width: 220px;
    }

    .search-field label {
        font-weight: 600;
        margin-bottom: .5rem;
        display: block;
    }

    .search-input,
    .price-input,
    select {
        width: 100%;
        padding: 0.6rem 0.9rem;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input:focus,
    .price-input:focus,
    select:focus {
        border-color: #28a745;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
        outline: none;
    }

    .reset-button {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .reset-btn {
        padding: 10px 24px;
        border: none;
        border-radius: 30px;
        background: linear-gradient(45deg, #28a745, #1abc9c);
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .reset-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.2);
    }

    .property-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .property-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .property-card img {
        transition: transform 0.3s ease;
    }

    .property-card:hover img {
        transform: scale(1.05);
    }

    .type-badge {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 20px;
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
    }

    @media (max-width: 768px) {
        .search-row {
            flex-direction: column;
        }
    }
</style>

<section class="container mt-5">
    <!-- Search Form -->
    <div class="search-container">
        <h4><i class="fas fa-home text-success me-2"></i> Cari Homestay Murah</h4>
        <form method="GET" action="{{ route('search_homestay') }}">
            <div class="search-field">
                <label>Kata Kunci</label>
                <input type="text" name="keyword" class="search-input" placeholder="Contoh: Homestay Bali" value="{{ request('keyword') }}">
            </div>
            <div class="search-row">
                <div class="search-field">
                    <label>Kota</label>
                    <select name="city_id" class="search-input">
                        <option value="">Semua Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->city_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="search-field">
                    <label>Urutkan</label>
                    <select name="order_by" class="search-input">
                        <option value="acak" {{ request('order_by') == 'acak' ? 'selected' : '' }}>Acak</option>
                        <option value="termurah" {{ request('order_by') == 'termurah' ? 'selected' : '' }}>Harga Termurah</option>
                        <option value="termahal" {{ request('order_by') == 'termahal' ? 'selected' : '' }}>Harga Termahal</option>
                    </select>
                </div>
                <div class="search-field">
                    <label>Harga</label>
                    <div class="d-flex gap-2">
                        <input type="number" name="price_min" class="price-input" placeholder="Rp 0" value="{{ request('price_min') }}">
                        <span class="align-self-center">-</span>
                        <input type="number" name="price_max" class="price-input" placeholder="Rp 10.000.000" value="{{ request('price_max') }}">
                    </div>
                </div>
            </div>
            <div class="reset-button">
                <button type="submit" class="reset-btn">Cari</button>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    <div class="mt-5">
        @if (empty($properties))
            <div class="text-center py-5 text-muted">
                <i class="fas fa-search fa-3x mb-3"></i>
                <h5>Tidak ada homestay ditemukan.</h5>
                <p>Coba ubah kata kunci atau filter pencarian Anda.</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-4 g-4">
                @foreach ($properties as $property)
                    <div class="col">
                        <a href="{{ route('detail-property.show', $property->property_id) }}" class="text-decoration-none">
                            <div class="card property-card h-100 position-relative">
                                <span class="type-badge">{{ $property->property_type }}</span>
                                <img src="{{ asset('storage/' . $property->image) }}" class="card-img-top" alt="{{ $property->property_name }}">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold">{{ $property->property_name }}</h6>
                                    <p class="text-muted small mb-1">{{ $property->subdistrict }}, Surabaya</p>
                                    <p class="text-danger fw-bold mb-0">Rp {{ number_format($property->min_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
