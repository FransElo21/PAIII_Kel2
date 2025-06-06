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

  .hero-section {
    position: relative;
    width: 100%;
    height: 100vh;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    overflow: hidden;
  }
  .hero-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 0;
  }
  .hero-section > .container,
  .hero-section > *:not(.hero-overlay) {
    position: relative;
    z-index: 1;
  }
  .hero-section h1,
  .hero-section .fw-bold {
    font-size: 2.5rem;
    font-weight: 700;
    color: #fff;
  }
  .hero-section p {
    font-size: 1.1rem;
    color: #fff;
  }
  .text-highlight {
    color: #00b894;
  }
  .search-input-group {
    border-radius: 50px;
    transition: all 0.3s ease;
    max-width: 600px;
    width: 100%;
    margin: 0 auto;
  }
  .search-input-group .form-control {
    border-radius: 50px 0 0 50px;
    border: none;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    background: #fff !important;
    color: #333;
    box-shadow: none;
  }
  .search-input-group .form-control::placeholder {
    color: #666;
    opacity: 1;
  }
  .search-input-group .btn {
    border-radius: 0 50px 50px 0;
    background: var(--secondary);
    color: white;
    transition: all 0.3s ease;
    font-size: 1.15rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
  .search-input-group .btn:hover {
    background: #21c493;
    transform: scale(1.05);
  }
  .category-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: all 0.3s ease;
    text-align: center;
    padding: 1rem;
  }
  .category-card img {
    height: 250px;
    object-fit: cover;
    width: 100%;
    border-radius: 1rem;
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
  .property-card {
    border-radius: var(--radius);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
    border: none;
    background: #fff;
  }
  .property-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
  }
  .property-card .card-img-top {
    transition: transform 0.4s ease;
    width: 100%;
    height: 180px;
    object-fit: cover;
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
  .property-card .star-rating i,
  .card .fa-star {
    color: #FFD700;
    font-size: 1rem;
  }
  .fade-in {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
    animation: fadeInUp 1s ease-in-out;
  }
  .fade-in.visible {
    opacity: 1;
    transform: translateY(0);
  }
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px);}
    to   { opacity: 1; transform: translateY(0);}
  }
  @media (max-width: 768px) {
    .hero-section { text-align: center; }
    .hero-section .col-md-6:last-child { margin-top: 2rem; }
    .search-input-group .form-control { border-radius: 50px !important; }
    .search-input-group .btn { border-radius: 50px !important; margin-top: 1rem;}
  }
</style>

<!-- HERO CAROUSEL -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
  <!-- Carousel Indicators -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <!-- Slide 1 -->
    <div class="carousel-item active">
      <section class="hero-section" style="background-image: url('/assets/images/auth/Pemandangan_Gunung_Bromo.jpg');">
        <div class="hero-overlay"></div>
        <div class="container d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 100vh;">
          <h1 class="fw-bold display-5 text-white">
            Hai kamu, <span class="text-highlight">Mau Cari Penginapan?</span>
          </h1>
          <p class="lead text-white fw-semibold mt-3">
            Hommie – Platform terbaik buat cari kost & homestay harian!
          </p>
          <form action="{{ route('search_welcomeProperty') }}" method="GET" class="search-input-group mt-4">
            <div class="input-group input-group-lg">
              <input type="text" name="keyword"
                class="form-control bg-white text-dark border-0"
                placeholder="Masukkan nama lokasi/area/alamat"
                value="{{ request('keyword') }}">
              <button class="btn btn-secondary px-4" type="submit">
                <i class="bi bi-search fs-5"></i>
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
    <!-- Slide 2 -->
    <div class="carousel-item">
      <section class="hero-section" style="background-image: url('/assets/images/auth/kosts.jpg');">
        <div class="hero-overlay"></div>
        <div class="container d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 100vh;">
          <h1 class="fw-bold display-5 text-white">
            Temukan <span class="text-highlight">kost impianmu</span>
          </h1>
          <p class="lead text-white fw-semibold mt-3">
            Dengan lokasi strategis dan harga terjangkau
          </p>
          <form action="{{ route('search_welcomeProperty') }}" method="GET" class="search-input-group mt-4">
            <div class="input-group input-group-lg">
              <input type="text" name="keyword"
                class="form-control bg-white text-dark border-0"
                placeholder="Masukkan nama lokasi/area/alamat"
                value="{{ request('keyword') }}">
              <button class="btn btn-secondary px-4" type="submit">
                <i class="bi bi-search fs-5"></i>
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
    <!-- Slide 3: Homestay -->
    <div class="carousel-item">
      <section class="hero-section" style="background-image: url('/assets/images/auth/home.jpg');">
        <div class="hero-overlay"></div>
        <div class="container d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 100vh;">
          <h1 class="fw-bold display-5 text-white">
            Booking <span class="text-highlight">Homestay Favoritmu</span>
          </h1>
          <p class="lead text-white fw-semibold mt-3">
            Mudah, cepat, dan banyak pilihan menarik untuk liburan & staycation!
          </p>
          <form action="{{ route('search_welcomeProperty') }}" method="GET" class="search-input-group mt-4">
            <div class="input-group input-group-lg">
              <input type="text" name="keyword"
                class="form-control bg-white text-dark border-0"
                placeholder="Masukkan nama lokasi/area/alamat"
                value="{{ request('keyword') }}">
              <button class="btn btn-secondary px-4" type="submit">
                <i class="bi bi-search fs-5"></i>
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
  <!-- Carousel Controls -->
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<div class="container">
  <!-- Categories Section -->
  <div class="container">
  <!-- Categories Section -->
  <section class="my-1 py-5 fade-in">
    <h4 class="fw-bold" style="color: #152C5B;">Hai, ada yang bisa dibantu ?</h4>
    <p>Anda bisa telusuri kategori informasi sesuai tipe akun berikut ini :</p>
    <div class="row g-4">
      <div class="col-12 col-md-6">
        <div class="category-card text-center p-3 shadow-sm rounded-4" role="button" data-bs-toggle="modal" data-bs-target="#modalOwner" style="transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
          <img src="assets/images/auth/owner.jpg" alt="Pemilik Kos"
              class="img-fluid rounded-4 mb-3"
              style="height: 250px; object-fit: cover; width: 100%;">
          <h6 class="fw-bold">Pemilik Properti</h6>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="category-card text-center p-3 shadow-sm rounded-4" role="button" data-bs-toggle="modal" data-bs-target="#modalTenant" style="transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
          <img src="assets/images/auth/customer.jpg" alt="Penyewa Kos"
              class="img-fluid rounded-4 mb-3"
              style="height: 250px; object-fit: cover; width: 100%;">
          <h6 class="fw-bold">Calon Penyewa</h6>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal: Pemilik Properti -->
<div class="modal fade" id="modalOwner" tabindex="-1" aria-labelledby="modalOwnerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      <div class="modal-header bg-gradient-primary text-white rounded-top-4 d-flex align-items-center">
        <i class="fas fa-building fa-3x me-3"></i>
        <h5 class="modal-title fw-bold fs-3" id="modalOwnerLabel">Pemilik Properti</h5>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body p-5">
        <p class="fs-5 text-secondary mb-4">
          Akun ini dirancang khusus untuk <strong>pemilik kos, homestay, rumah sewa, atau apartemen</strong> yang ingin memasarkan properti mereka melalui platform Hommie. 
          Dengan akun ini, Anda memiliki kendali penuh atas informasi dan penawaran properti Anda. 
          Platform kami memberikan kemudahan dalam mengelola data kamar, menetapkan harga, mengunggah foto, dan memantau pemesanan secara real-time.
        </p>
        <p class="fs-6 text-muted">
          Manfaat menjadi pemilik properti di Hommie:
        </p>
        <ul class="list-unstyled ps-4 mb-4">
          <li class="mb-2 d-flex align-items-center">
            <i class="fas fa-check-circle text-success me-2 fs-5"></i>
            Menjangkau ribuan calon penyewa dari berbagai kota
          </li>
          <li class="mb-2 d-flex align-items-center">
            <i class="fas fa-check-circle text-success me-2 fs-5"></i>
            Mengelola properti Anda dengan dashboard yang intuitif
          </li>
          <li class="mb-2 d-flex align-items-center">
            <i class="fas fa-check-circle text-success me-2 fs-5"></i>
            Menerima laporan pemesanan dan pembayaran otomatis
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Calon Penyewa -->
<div class="modal fade" id="modalTenant" tabindex="-1" aria-labelledby="modalTenantLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      <div class="modal-header bg-gradient-success text-white rounded-top-4 d-flex align-items-center">
        <i class="fas fa-user-check fa-3x me-3"></i>
        <h5 class="modal-title fw-bold fs-3" id="modalTenantLabel">Calon Penyewa</h5>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body p-5">
        <p class="fs-5 text-secondary mb-4">
            Akun ini ditujukan untuk Anda yang sedang mencari hunian sementara seperti <strong>kos harian, kos bulanan, homestay, atau tempat tinggal untuk liburan</strong>. 
            Dengan akun penyewa, Anda bisa mencari properti yang sesuai dengan preferensi Anda, melakukan pemesanan langsung, serta memberikan ulasan setelah menginap.
          </p>
          <p class="fs-6 text-muted">
            Keuntungan menjadi calon penyewa:
          </p>
          <ul class="list-unstyled ps-4 mb-4">
            <li class="mb-2 d-flex align-items-center">
              <i class="fas fa-search-location text-success me-2 fs-5"></i>
              Mencari dan menyaring properti berdasarkan lokasi, harga, dan fasilitas
            </li>
            <li class="mb-2 d-flex align-items-center">
              <i class="fas fa-calendar-check text-success me-2 fs-5"></i>
              Melakukan pemesanan dan pembayaran secara aman & cepat
            </li>
            <li class="mb-2 d-flex align-items-center">
              <i class="fas fa-comments text-success me-2 fs-5"></i>
              Memberikan ulasan dan rating untuk membantu pengguna lain
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
  <!-- Properties Section -->
  <section class="my-1 py-1 fade-in">
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
                              <small class="text-muted mb-2">{{ $property->alamat_selengkapnya }}</small>
                              <small class="text-muted mb-2">
                                {{ $property->district }}, {{ $property->city }}
                              </small>

                              <!-- Rating Section -->
                              <div class="d-flex align-items-center mt-2">
                                  <div class="me-2">
                                      @php
                                          $avgRating = round($property->avg_rating, 1);
                                          $fullStars = floor($avgRating);
                                          $halfStar = ($avgRating - $fullStars) >= 0.5 ? true : false;
                                      @endphp
                                      @for ($i = 1; $i <= $fullStars; $i++)
                                          <i class="fas fa-star text-warning me-1"></i>
                                      @endfor
                                      @if ($halfStar)
                                          <i class="fas fa-star-half-alt text-warning me-1"></i>
                                          @php $fullStars++ @endphp
                                      @endif
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
