@extends('layouts.index-welcome')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .search-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }
    .search-field {
        flex: 1;
        min-width: 200px;
    }
    .search-field label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .search-input, .price-input, select.search-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }
    .search-input:focus, .price-input:focus, select.search-input:focus {
        outline: none;
        border-color: #28a745;
        box-shadow: 0 0 4px rgba(40,167,69,0.2);
    }
    .price-range {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .reset-button {
        margin-top: 15px;
        display: flex;
        justify-content: flex-end;
    }
    .reset-btn {
        background-color: white;
        color: #28a745;
        border: 1px solid #28a745;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.3s;
    }
    .reset-btn:hover {
        background-color: #28a745;
        color: white;
    }
    .property-card:hover img {
        transform: scale(1.05);
    }
    .property-card {
        transition: all 0.3s ease;
    }
    .property-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .type-badge {
        background-color: #28a745;
        color: white;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 12px;
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
    }
</style>

<section class="container">
    <section class="container">
        <div class="search-container">
            <h4 class="fw-bold mb-3">
                <i class="fas fa-home me-2 text-success"></i> Cari Homestay Murah
            </h4>
    
            <!-- Form pencarian -->
            <form method="GET" action="{{ route('search') }}">
                <div class="search-field">
                    <label class="form-label">Masukkan Kata Kunci</label>
                    <div class="position-relative">
                        <input type="text" 
                               name="keyword"
                               class="form-control pe-4" 
                               value="{{ request('keyword') }}" 
                               placeholder="Masukkan nama Homestay">
                        <i class="fas fa-search position-absolute end-0 top-50 translate-middle-y me-3 text-secondary"></i>
                    </div>
                </div>
    
                <div class="search-row">
                    <div class="search-field">
                        <label>Kota & Area</label>
                        <select name="city_id" class="search-input">
                            <option value="">Semua Kota</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
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
                        <div class="price-range">
                            <input type="number" name="price_min" class="price-input" value="{{ request('price_min') }}" placeholder="Rp 0">
                            <span>-</span>
                            <input type="number" name="price_max" class="price-input" value="{{ request('price_max') }}" placeholder="Rp 10.000.000">
                        </div>
                    </div>
                </div>
    
                <!-- Tombol submit -->
                <div class="reset-button">
                    <button type="submit" class="reset-btn">Cari</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Menampilkan hasil pencarian properti -->
    <div class="row row-cols-1 row-cols-md-4 g-4 mt-4">
        @foreach ($properties as $property)
            <div class="col fade-in">
                <a href="{{ route('detail-property.show', $property->property_id) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden property-card position-relative">
                        <span class="type-badge">{{ $property->property_type }}</span>
                        <div class="overflow-hidden">
                            <img src="{{ asset('storage/' . $property->image) }}" 
                                 class="card-img-top object-fit-cover" 
                                 style="height: 220px; object-fit: cover; transition: 0.3s ease;" 
                                 alt="{{ $property->property_name }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-1 fw-bold text-dark">{{ $property->property_name }}</h6>
                            <small class="text-muted mb-2">Kecamatan {{ $property->subdistrict }}</small>
                            <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">{{ Str::limit($property->description, 80) }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

@endsection
