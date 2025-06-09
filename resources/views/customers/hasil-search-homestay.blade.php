@extends('layouts.index-welcome')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
  /* ===========================
     Variabel Warna & Typography
     =========================== */
  :root {
    --primary: #152C5B;          /* Biru tua */
    --success: #28A745;          /* Hijau lembut */
    --light-bg: #F8F9FA;         /* Latar umum */
    --dark-text: #343A40;        /* Teks utama */
    --muted-text: #6C757D;       /* Teks sekunder */
    --radius: 12px;              /* Radius umum */
    --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    --transition-fast: 0.2s ease-in-out;
    --transition-med: 0.4s ease-in-out;
    --font-base: 'Poppins', sans-serif;
  }

  body {
    background-color: var(--light-bg);
    font-family: var(--font-base);
    color: var(--dark-text);
  }

  /* ===========================
     Search Container
     =========================== */
  .search-container {
    background-color: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    padding: 2rem;
    margin-top: 2rem;
    margin-bottom: 2rem;
    transition: box-shadow var(--transition-med), transform var(--transition-med);
  }

  .search-container:hover {
    box-shadow: var(--shadow-md);
  }

  .search-container h4 {
    font-weight: 700;
    font-size: 1.5rem;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
  }

  .search-container h4 i {
    color: var(--success);
    font-size: 1.5rem;
  }

  /* ===========================
     Search Form & Field Styling
     =========================== */
  .search-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: flex-end;
  }

  .search-field {
    flex: 1;
    min-width: 200px;
  }

  .search-field label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.4rem;
    color: var(--dark-text);
  }

  .search-input,
  .price-input,
  select.search-input,
  input[type="number"] {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #CED4DA;
    border-radius: var(--radius);
    font-size: 0.95rem;
    color: var(--dark-text);
    background-color: #FFF;
    transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
  }

  .search-input:focus,
  .price-input:focus,
  select.search-input:focus,
  input[type="number"]:focus {
    outline: none;
    border-color: var(--success);
    box-shadow: 0 0 0 0.2rem rgba(40, 154, 132, 0.2);
  }

  /* Hilangkan panah pada input number */
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .price-range {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .price-range span {
    font-size: 0.9rem;
    color: var(--muted-text);
  }

  .reset-button {
    margin-top: 1.5rem;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
  }

  .reset-btn {
    background: linear-gradient(45deg, var(--success), #21c493);
    color: #FFF;
    border: none;
    padding: 0.6rem 1.4rem;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 50px;
    cursor: pointer;
    transition: background var(--transition-fast), transform var(--transition-fast), box-shadow var(--transition-fast);
  }

  .reset-btn:hover {
    background: linear-gradient(45deg, #21c493, #1eaa8a);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
  }

  .reset-link {
    background-color: #FFF;
    color: var(--muted-text);
    border: 1px solid #CED4DA;
    padding: 0.6rem 1.4rem;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 50px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: background var(--transition-fast), color var(--transition-fast), box-shadow var(--transition-fast);
  }

  .reset-link:hover {
    background-color: #F1F1F1;
    box-shadow: var(--shadow-sm);
  }

  /* ===========================
     Gallery Main (Card Image Container)
     =========================== */
  .gallery-main {
    overflow: hidden;
    border-radius: var(--radius);
    height: 220px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 0.75rem;
    transition: box-shadow var(--transition-fast);
  }

  .gallery-main:hover {
    box-shadow: var(--shadow-md);
  }

  .gallery-main img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-med);
  }

  .gallery-main:hover img {
    transform: scale(1.05);
  }

  /* ===========================
     Property Card Styling
     =========================== */
  .property-card {
    border: none;
    border-radius: var(--radius);
    background-color: #FFF;
    transition: transform var(--transition-med), box-shadow var(--transition-med);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .property-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
  }

  .type-badge {
    background-color: var(--success);
    color: #FFF;
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 12px;
    position: absolute;
    top: 10px;
    left: 10px;
    font-weight: 500;
    box-shadow: var(--shadow-sm);
    z-index: 10;
  }

  .property-card .card-body {
    padding: 0.75rem 1rem 1rem 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .property-card .card-title {
    margin-bottom: 0.25rem;
    font-size: 1rem;
    font-weight: 700;
    color: var(--dark-text);
  }

  .property-card .text-muted {
    font-size: 0.85rem;
    color: var(--muted-text) !important;
  }

  .property-card .btn-success {
    background-color: var(--success);
    border: none;
    font-weight: 600;
    border-radius: 20px;
    transition: background var(--transition-fast), transform var(--transition-fast);
  }

  .property-card .btn-success:hover {
    background-color: #21c493;
    transform: translateY(-1px);
  }

  /* ===========================
     Fade-in Animation
     =========================== */
  .fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.8s ease, transform 0.8s ease;
  }

  .fade-in.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* ===========================
     Responsive Adjustments
     =========================== */
  @media (max-width: 768px) {
    .search-row {
      flex-direction: column;
    }

    .reset-button {
      justify-content: center;
    }

    .gallery-main {
      height: 180px;
    }
  }
</style>

<!-- ===========================
     Bagian Search Form
    =========================== -->
<section class="container">
  <div class="search-container fade-in">
    <h4>
      <i class="fas fa-home"></i> Cari Homestay Murah
    </h4>

    <form method="GET" action="{{ route('search_homestay') }}">
      <div class="search-field mb-3">
        <label>Masukkan Kata Kunci</label>
        <div class="position-relative">
          <input
            type="text"
            name="keyword"
            class="search-input ps-4 pe-4"
            value="{{ request('keyword') }}"
            placeholder="Masukkan nama Homestay">
          <i class="fas fa-search position-absolute end-0 top-50 translate-middle-y me-3 text-muted"></i>
        </div>
      </div>

      <div class="search-row">
        <div class="search-field">
          <label>Kota & Area</label>
          <select name="city_id" class="search-input">
            <option value="">Semua Kota</option>
            @foreach($cities as $city)
              <option
                value="{{ $city->id }}"
                {{ request('city_id') == $city->id ? 'selected' : '' }}>
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
          <div class="price-range">
            <input
              type="number"
              name="price_min"
              class="price-input"
              value="{{ request('price_min') }}"
              placeholder="Rp 0"
              min="0">
            <span>–</span>
            <input
              type="number"
              name="price_max"
              class="price-input"
              value="{{ request('price_max') }}"
              placeholder="Rp 10.000.000"
              min="0">
          </div>
        </div>
      </div>

      <div class="reset-button">
        <button type="submit" class="reset-btn">Cari</button>
        <a href="{{ route('search_homestay') }}" class="reset-link">Reset</a>
      </div>
    </form>
  </div>
</section>

<!-- ===========================
     Bagian Daftar Homestay (Perulangan)
    =========================== -->
<section class="container mt-5 mb-5">
  <h3 class="fw-bold mb-4 fade-in">Daftar Homestay</h3>
  
  <div class="row row-cols-1 row-cols-md-4 g-4">
    @foreach ($properties as $property)
      <div class="col fade-in">
        <a href="{{ route('detail-property.show', $property->property_id) }}" class="text-decoration-none">
          <div class="card property-card">
            <!-- Badge Tipe Properti -->
            <span class="type-badge">
              {{ $property->property_type }}
            </span>
            <!-- Gambar Properti -->
            <img 
              src="{{ asset('storage/' . $property->image) }}"
              class="card-img-top"
              alt="{{ $property->property_name }}">
            
            <!-- Isi Card -->
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h6 class="card-title">{{ $property->property_name }}</h6>
                <small class="text-muted">{{ $property->subdistrict }}, Surabaya</small>
                
                <!-- Rating Section -->
                <div class="d-flex align-items-center mt-2">
                  <div class="rating-stars d-flex">
                    @for($i=1; $i <= 5; $i++)
                      <i class="fas fa-star"></i>
                    @endfor
                  </div>
                  <span class="rating-text">rb Ulasan</span>
                </div>
              </div>
              
              <div class="mt-3">
                <p class="mb-0">
                  <span class="price-tag">
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
</section>

<!-- ===========================
     Fade-in Script
    =========================== -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in');

    const onScroll = () => {
      const triggerPoint = window.innerHeight * 0.9;
      fadeElements.forEach(el => {
        const top = el.getBoundingClientRect().top;
        if (top < triggerPoint) {
          el.classList.add('visible');
        } else {
          el.classList.remove('visible');
        }
      });
    };

    window.addEventListener('scroll', onScroll);
    onScroll();
  });
</script>
@endsection
