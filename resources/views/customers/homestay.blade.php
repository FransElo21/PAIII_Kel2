@extends('layouts.index-welcome')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
  :root {
    --primary: #152C5B;
    --success: #289A84;
    --light: #F8F9FA;
    --dark: #343A40;
    --gray-muted: #6C757D;
    --radius: 12px;
    --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    --transition-medium: 0.4s ease-in-out;
    --font-base: 'Poppins', sans-serif;
  }
  body { background-color: var(--light); font-family: var(--font-base); color: var(--dark);}
  h3, h4, h6 { color: var(--primary);}
  .card, .search-container { border-radius: var(--radius); box-shadow: var(--shadow-sm); background-color: #fff; transition: box-shadow var(--transition-medium), transform var(--transition-medium);}
  .card:hover, .search-container:hover { box-shadow: var(--shadow-md); transform: translateY(-2px);}
  .search-container { padding: 2rem; margin-bottom: 3rem;}
  .search-container h4 { font-weight: 700; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;}
  .search-container h4 i { color: var(--success); font-size: 1.5rem;}
  .search-row { display: flex; flex-wrap: wrap; gap: 1.5rem;}
  .search-field { flex: 1; min-width: 200px;}
  .search-field label { display: block; font-size: 0.9rem; font-weight: 600; color: var(--dark); margin-bottom: 0.4rem;}
  .search-input, .price-input, select.search-input { width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius); border: 1px solid #CED4DA; font-size: 0.95rem; color: var(--dark); transition: border-color 0.2s, box-shadow 0.2s;}
  .search-input:focus, .price-input:focus, select.search-input:focus { border-color: var(--success); box-shadow: 0 0 0 0.2rem rgba(40, 154, 132, 0.2); outline: none;}
  .price-range { display: flex; align-items: center; gap: 0.5rem;}
  .price-range span { color: var(--gray-muted); font-size: 0.9rem;}
  .reset-button { margin-top: 1.5rem; display: flex; justify-content: flex-end;}
  .reset-btn { background: linear-gradient(45deg, var(--success), #21c493); color: #fff; border: none; padding: 0.6rem 1.5rem; font-weight: 600; font-size: 0.95rem; border-radius: 50px; cursor: pointer; transition: background 0.2s, transform 0.2s, box-shadow 0.2s;}
  .reset-btn:hover { background: linear-gradient(45deg, #21c493, #1eaa8a); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(40, 154, 132, 0.3);}
  .property-card { border: none; overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: transform var(--transition-medium), box-shadow var(--transition-medium); position: relative;}
  .property-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md);}
  .property-card .card-img-top { width: 100%; height: 180px; object-fit: cover; transition: transform var(--transition-medium);}
  .property-card:hover .card-img-top { transform: scale(1.05);}
  .property-card .card-body { flex: 1; display: flex; flex-direction: column; justify-content: space-between; padding: 1rem;}
  .property-card .card-title { color: #000 !important; font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem;}
  .property-card .price-tag { color: #dc3545 !important; font-size: 1.2rem; font-weight: 700;}
  .type-badge { background: var(--success); color: #fff; font-size: 0.75rem; padding: 4px 10px; border-radius: 12px; position: absolute; top: 10px; left: 10px; font-weight: 500; box-shadow: var(--shadow-sm);}
  .property-card .text-muted { color: var(--gray-muted) !important; font-size: 0.9rem;}
  .property-card .rating-stars i { color: #FDD835; margin-right: 2px; font-size: 0.9rem;}
  .property-card .rating-text { font-size: 0.85rem; color: var(--gray-muted); margin-left: 0.25rem;}
  .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.8s ease, transform 0.8s ease;}
  .fade-in.visible { opacity: 1; transform: translateY(0);}
  @media (max-width: 768px) {
    .search-row { flex-direction: column;}
    .price-range { flex-direction: column; align-items: flex-start;}
    .price-range span { display: none;}
    .property-card .card-img-top { height: 160px;}
  }
</style>

<section class="container mt-5">
  <div class="search-container fade-in">
    <h4>
      <i class="fas fa-home"></i> Cari Homestay Murah
    </h4>
    <form method="GET" action="{{ route('search_homestay') }}">
      <div class="search-field mb-4">
        <label>Masukkan Kata Kunci</label>
        <div class="position-relative">
          <input 
            type="text"
            name="keyword"
            class="search-input ps-4 pe-5"
            value="{{ request('keyword') }}"
            placeholder="Masukkan nama Homestay">
          <i class="fas fa-search position-absolute end-0 top-50 translate-middle-y me-3 text-gray-muted"></i>
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
              type="text"
              name="price_min"
              class="price-input"
              value="{{ request('price_min') ? 'Rp ' . number_format(request('price_min'), 0, ',', '.') : '' }}"
              placeholder="Rp 0">
            <span>-</span>
            <input 
              type="text"
              name="price_max"
              class="price-input"
              value="{{ request('price_max') ? 'Rp ' . number_format(request('price_max'), 0, ',', '.') : '' }}"
              placeholder="Rp 10.000.000">
          </div>
        </div>
      </div>

      <div class="reset-button">
        <button type="submit" class="reset-btn">Cari</button>
      </div>
    </form>
  </div>
</section>

<section class="container mt-5 mb-5">
  <h3 class="fw-bold mb-4 fade-in">Daftar Homestay</h3>
  <div class="row row-cols-1 row-cols-md-4 g-4">
    @foreach ($properties as $property)
      <div class="col fade-in">
        <a href="{{ route('detail-property.show', $property->property_id) }}" class="text-decoration-none">
          <div class="card property-card">
            <span class="type-badge">
              {{ $property->property_type }}
            </span>
            <img 
              src="{{ asset('storage/' . $property->image) }}"
              class="card-img-top"
              alt="{{ $property->property_name }}">
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h6 class="card-title">{{ $property->property_name }}</h6>
                <small class="text-muted">{{ $property->subdistrict }}, Surabaya</small>
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

<script>
  // Animasi Fade In
  document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in');
    const checkFade = () => {
      const triggerBottom = window.innerHeight * 0.9;
      fadeElements.forEach(element => {
        const boxTop = element.getBoundingClientRect().top;
        if (boxTop < triggerBottom) {
          element.classList.add('visible');
        } else {
          element.classList.remove('visible');
        }
      });
    };
    window.addEventListener('scroll', checkFade);
    checkFade();
  });

  // Format Harga & Kirim ke Controller angka saja
  document.addEventListener('DOMContentLoaded', function () {
    // Saat user mengetik, tetap format (Rp 120.000)
    document.querySelectorAll('.price-input').forEach(function(input) {
      input.addEventListener('input', function() {
        let digits = this.value.replace(/\D/g, '');
        digits = digits.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        this.value = digits ? 'Rp ' + digits : '';
      });
    });

    // Saat submit, hapus "Rp", titik, dsb (hanya angka)
    document.querySelector('form').addEventListener('submit', function(e) {
      document.querySelectorAll('.price-input').forEach(function(input) {
        input.value = input.value.replace(/[^\d]/g, '');
      });
    });
  });
</script>
@endsection
