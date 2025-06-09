@extends('layouts.index-welcome')

@section('content')

<!-- Import Google Font Inter untuk tampilan lebih modern -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
  :root {
    --primary: #152C5B;
    --secondary: #289A84;
    --light-bg: #F8F9FA;
    --dark-text: #343A40;
    --radius: 16px;
    --shadow-light: 0 4px 10px rgba(0, 0, 0, 0.05);
    --shadow-medium: 0 6px 20px rgba(0, 0, 0, 0.08);
    --transition-fast: 0.25s ease;
    --font-family: 'Inter', sans-serif;
  }

  body {
    background-color: var(--light-bg);
    font-family: var(--font-family);
    color: var(--dark-text);
    overflow-x: hidden;
  }

  .container {
    padding-top: 2rem;
    padding-bottom: 2rem;
  }

  /* Breadcrumb */
  .breadcrumb {
    background: #ffffff;
    border-radius: var(--radius);
    padding: 0.75rem 1rem;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
    box-shadow: var(--shadow-light);
  }
  .breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #94a3b8;
    margin: 0 0.5rem;
  }
  .breadcrumb-item a {
    color: #64748b;
    transition: color var(--transition-fast);
    text-decoration: none;
  }
  .breadcrumb-item a:hover {
    color: var(--secondary);
  }
  .breadcrumb-item.active {
    color: var(--secondary);
    font-weight: 600;
  }

  /* Search Form */
  .search-input-group {
    max-width: 800px;
    margin: 0 auto 2rem;
  }
  .search-input-group .form-control {
    border-radius: 50px 0 0 50px;
    border: 1px solid #e2e8f0;
    border-right: none;
    padding-left: 1.5rem;
    font-size: 1rem;
    height: 48px;
    box-shadow: var(--shadow-light);
    transition: box-shadow var(--transition-fast), border-color var(--transition-fast);
  }
  .search-input-group .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(40,154,132,0.2);
    border-color: var(--secondary);
  }
  .search-input-group .btn {
    border-radius: 0 50px 50px 0;
    background-color: var(--secondary);
    color: #ffffff;
    padding: 0.5rem 1.5rem;
    border: 1px solid var(--secondary);
    height: 48px;
    font-size: 1rem;
    box-shadow: var(--shadow-light);
    transition: background-color var(--transition-fast), transform var(--transition-fast), box-shadow var(--transition-fast);
  }
  .search-input-group .btn:hover {
    background-color: #21c493;
    border-color: #21c493;
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
  }

  /* Two-column layout */
  .search-results {
    display: flex;
    gap: 1rem;
  }
  .results-list {
    flex: 0 0 75%;
    max-height: calc(100vh - 240px);
    overflow-y: auto;
    padding-right: 0.5rem;
  }
  .results-map {
    flex: 0 0 25%;
    position: relative;
  }
  #map {
    width: 100%;
    height: calc(100vh - 240px);
    border-radius: var(--radius);
    background: #f5f6fa;
    box-shadow: var(--shadow-light);
  }

  /* Property Card */
  .property-card {
    border-radius: var(--radius);
    overflow: hidden;
    transition: transform var(--transition-fast), box-shadow var(--transition-fast);
    border: none;
    background-color: #ffffff;
    cursor: pointer;
    color: inherit;
    margin-bottom: 1.5rem;
    display: flex;
    box-shadow: var(--shadow-light);
  }
  .property-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-medium);
  }
  .property-card .col-4,
  .property-card .col-md-4 {
    padding: 0;
  }
  .property-card .card-img-top {
    height: 100%;
    width: 100%;
    object-fit: cover;
    transition: transform var(--transition-fast);
  }
  .property-card:hover .card-img-top {
    transform: scale(1.03);
  }

  .property-info {
    padding: 1rem 1.25rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex-grow: 1;
  }
  .property-info .title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.5rem;
    line-height: 1.3;
    transition: color var(--transition-fast);
  }
  .property-card:hover .property-info .title {
    color: var(--secondary);
  }
  .property-info .location {
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 0.75rem;
  }
  .rating-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
  }
  .rating {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: #555;
  }
  .rating i {
    color: #FFD700;
    margin-right: 2px;
  }
  .rating .score {
    font-weight: 600;
    margin-right: 6px;
    color: var(--dark-text);
  }
  .price {
    font-size: 1.125rem;
    font-weight: 700;
    color: #dc3545;
  }

  /* Label Tipe (badge) */
  .badge-type {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    padding: 0.25rem 0.6rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #ffffff;
    box-shadow: var(--shadow-light);
    text-transform: uppercase;
  }
  .badge-homestay {
    background-color: var(--secondary);
  }
  .badge-kost {
    background-color: var(--primary);
  }
  .badge-other {
    background-color: #6c757d;
  }

  /* No results */
  .no-results {
    background: #ffffff;
    border-radius: var(--radius);
    padding: 3rem 1rem;
    text-align: center;
    box-shadow: var(--shadow-light);
  }
  .no-results i {
    color: var(--secondary);
    margin-bottom: 1rem;
  }
  .no-results h5 {
    margin-bottom: 0.5rem;
    color: var(--dark-text);
    font-weight: 600;
  }
  .no-results p {
    color: #64748b;
    font-size: 0.95rem;
  }

  /* Responsive adjustments */
  @media (max-width: 991.98px) {
    .search-results {
      flex-direction: column;
    }
    .results-list, .results-map #map {
      max-height: none;
      height: 400px;
    }
    #map {
      margin-top: 1rem;
    }
  }
  @media (max-width: 576px) {
    .search-input-group {
      max-width: 95%;
    }
    .search-input-group .form-control {
      font-size: 0.95rem;
      height: 44px;
    }
    .search-input-group .btn {
      padding: 0.5rem 1rem;
      font-size: 0.95rem;
      height: 44px;
    }
    .property-info .title {
      font-size: 1rem;
    }
    .property-info .location {
      font-size: 0.85rem;
    }
    .price {
      font-size: 1rem;
    }
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
      <input
        type="text"
        name="keyword"
        class="form-control"
        value="{{ request('keyword') }}"
        placeholder="Cari lokasi, area, atau alamat…"
      />
      <button class="btn" type="submit">
        <i class="bi bi-search"></i>
      </button>
    </form>
  </div>

  <div class="search-results">
    <!-- Left: Property List -->
    <div class="results-list">
      @if($paginatedProperties->isEmpty())
        <div class="no-results">
          <i class="fas fa-house-user fa-3x"></i>
          <h5>Tidak ada properti ditemukan</h5>
          <p>Coba kata kunci lain atau ubah filter pencarian.</p>
        </div>
      @else
        @foreach ($paginatedProperties as $property)
          <a href="{{ route('detail-property.show', $property->property_id) }}" class="property-card">
            <div class="row g-0 align-items-center">
              <!-- Image -->
              <div class="col-4 col-md-4 position-relative">
                <img
                  src="{{ asset('storage/' . $property->image) }}"
                  alt="{{ $property->property_name }}"
                  class="card-img-top"
                />
                @php $type = $property->property_type; @endphp
                @if($type === 'Homestay')
                  <span class="badge-type badge-homestay">{{ $type }}</span>
                @elseif($type === 'Kost')
                  <span class="badge-type badge-kost">{{ $type }}</span>
                @else
                  <span class="badge-type badge-other">{{ $type }}</span>
                @endif
              </div>

              <!-- Details -->
              <div class="col-8 col-md-8">
                <div class="property-info">
                  <div>
                    <div class="title">{{ $property->property_name }}</div>
                    <div class="location">
                      <i class="fas fa-map-marker-alt me-1"></i>
                      {{ $property->subdistrict }}, {{ $property->city }}
                    </div>
                  </div>
                  <div class="rating-price">
                    <div class="rating">
                      @php
                        $avgRating = round($property->avg_rating, 1);
                        $fullStars = floor($avgRating);
                        $halfStar = ($avgRating - $fullStars) >= 0.5;
                      @endphp
                      <span class="score">{{ $avgRating }}</span>
                      @for ($i = 1; $i <= $fullStars; $i++)
                        <i class="fas fa-star"></i>
                      @endfor
                      @if ($halfStar)
                        <i class="fas fa-star-half-alt"></i>
                      @endif
                      @for ($i = $fullStars + $halfStar + 1; $i <= 5; $i++)
                        <i class="far fa-star"></i>
                      @endfor
                      <span class="ms-2" style="font-size:0.85rem; color:#64748b;">
                        ({{ $property->total_reviews ?? 0 }})
                      </span>
                    </div>
                    <div class="price">IDR {{ number_format($property->min_price) }}</div>
                  </div>
                </div>
              </div>
            </div>
          </a>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
          {{ $paginatedProperties->links() }}
        </div>
      @endif
    </div>

    <!-- Right: Map -->
    <div class="results-map">
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

  let firstLat = properties.length > 0 && properties[0].lat ? properties[0].lat : -2.5489;
  let firstLng = properties.length > 0 && properties[0].lng ? properties[0].lng : 118.0149;

  var map = L.map('map').setView([firstLat, firstLng], 11);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  properties.forEach(function(property) {
    if (property.lat && property.lng) {
      var marker = L.marker([property.lat, property.lng]).addTo(map);
      marker.bindPopup(`
        <div style="min-width:180px">
          <img src="${property.image}" alt="${property.name}"
               style="width:100%; height:90px; object-fit:cover; border-radius:8px; margin-bottom:6px;" />
          <strong style="font-family: var(--font-family); font-size: 0.95rem;">${property.name}</strong><br/>
          <small style="color:#64748b; font-size:0.85rem;">${property.address}</small>
        </div>
      `);
    }
  });
</script>

@endsection
