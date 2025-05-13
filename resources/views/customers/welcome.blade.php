@extends('layouts.index-welcome')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
  :root {
    --primary: #152C5B;
    --secondary: #289A84;
    --light: #F8F9FA;
    --dark: #343A40;
    --radius: 12px;
    --shadow-sm: 0 2px 6px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  /* Hero Section */
  .hero-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 249, 250, 0.95));
    border-radius: var(--radius);
    /* box-shadow: var(--shadow-md); */
    overflow: hidden;
    margin-top: -3rem;
    padding: 2rem;
  }

  .hero-section h2 {
    font-size: 2.5rem;
    color: var(--primary);
    font-weight: 700;
  }

  .hero-section p {
    color: var(--dark);
    font-size: 1.1rem;
  }

  .search-input-group {
    border-radius: 50px;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
  }

  .search-input-group:hover {
    box-shadow: var(--shadow-md);
  }

  .search-input-group .form-control {
    border-radius: 50px 0 0 50px;
    border: none;
    padding: 1rem 1.5rem;
    font-size: 1rem;
  }

  .search-input-group .btn {
    border-radius: 0 50px 50px 0;
    background: var(--secondary);
    color: white;
    transition: all 0.3s ease;
  }

  .search-input-group .btn:hover {
    background: #21c493;
    transform: scale(1.05);
  }

  /* Categories Section */
  .category-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .category-card img {
    transition: transform 0.4s ease;
  }

  .category-card:hover img {
    transform: scale(1.05);
  }

  .category-card h6 {
    color: var(--primary);
    font-weight: 600;
    margin-top: 0.5rem;
  }

  /* Properties Section */
  .property-card {
    border-radius: var(--radius);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
    border: none;
  }

  .property-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
  }

  .property-card .card-img-top {
    transition: transform 0.4s ease;
  }

  .property-card:hover .card-img-top {
    transform: scale(1.05);
  }

  .property-card .badge {
    background: linear-gradient(45deg, var(--secondary), #21c493);
  }

  .property-card .card-title {
    color: var(--primary);
    font-weight: 700;
  }

  .property-card .text-muted {
    color: #666 !important;
  }

  .property-card .price {
    color: var(--secondary);
    font-weight: 700;
    font-size: 1.2rem;
  }

  .property-card .star-rating i {
    color: #FFD700;
  }

  /* Fade-In Animation */
  .fade-in {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
  }

  .fade-in.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* Responsive Adjustments */
  @media (max-width: 768px) {
    .hero-section {
      margin-top: 0;
      text-align: center;
    }

    .hero-section .col-md-6:last-child {
      margin-top: 2rem;
    }

    .search-input-group .form-control {
      border-radius: 50px;
    }

    .search-input-group .btn {
      border-radius: 50px;
      margin-top: 1rem;
    }
  }
  /* Efek Bintang */
  .card .fa-star {
      font-size: 1rem;
  }

  /* Transisi saat hover */
  .property-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
  }
</style>

<div class="container mt-2">
    <!-- Hero Section -->
  <section class="container hero-section fade-in">
    <div class="row align-items-center g-5">
      <!-- Left Column -->
      <div class="col-md-6">
        <h2>Mau Cari Penginapan?</h2>
        <p>Dapatkan infonya dan langsung sewa di <strong style="color: var(--secondary);">Hommie</strong>.</p>
        
        <!-- Search Form -->
        <form action="{{ route('search_welcomeProperty') }}" method="GET" class="search-input-group">
          <div class="input-group">
            <input type="text" 
                   name="keyword"
                   class="form-control" 
                   value="{{ request('keyword') }}" 
                   placeholder="Masukkan nama lokasi/area/alamat">
            <button class="btn" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </form>
      </div>

      <!-- Right Column -->
      <div class="col-md-6 d-none d-md-block">
        <img src="assets/images/auth/cover-login.jpg" 
            alt="Gambar Kos" 
            class="img-fluid rounded-4 shadow-sm" 
            style="width: 100%; height: auto;">
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="container my-5 fade-in">
    <h4 class="fw-bold" style="color: #152C5B;">Hai, ada yang bisa dibantu ?</h4>
    <p>Anda bisa telusuri kategori informasi sesuai tipe akun berikut ini :</p>
    
    <div class="row g-4">
      <div class="col-12 col-md-6">
        <div class="category-card text-center p-3">
          <img src="assets/images/auth/owner.jpg" 
              alt="Pemilik Kos" 
              class="img-fluid rounded-4 mb-3" 
              style="height: 250px; object-fit: cover; width: 100%;">
          <h6>Pemilik Kos</h6>
        </div>
      </div>
      
      <div class="col-12 col-md-6">
        <div class="category-card text-center p-3">
          <img src="assets/images/auth/customer.jpg" 
              alt="Penyewa Kos" 
              class="img-fluid rounded-4 mb-3" 
              style="height: 250px; object-fit: cover; width: 100%;">
          <h6>Penyewa Kos</h6>
        </div>
      </div>
    </div>
  </section>

  <!-- Properties Section -->
  <section class="container mt-5 fade-in">
    <h4 class="fw-bold mb-4" style="color: #152C5B;">Mau Cari Penginapan Apa?</h4>
    
    <div class="row row-cols-1 row-cols-md-4 g-4">
      @foreach ($properties as $property)
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
  </section>
</div>

<!-- Fade-In Script -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in');

    const checkFade = () => {
      const triggerBottom = window.innerHeight / 5 * 4;

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
</script>
@endsection
