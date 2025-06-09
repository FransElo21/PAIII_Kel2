@extends('layouts.index-welcome')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
  /* Root variables */
  :root {
    --primary: #152C5B;
    --secondary: #289A84;
    --light-bg: #F8F9FA;
    --dark-text: #343A40;
    --radius: 12px;
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
  }

  body {
    overflow-x: hidden;
  }

  /* HERO SECTION */
  .hero-section {
    position: relative;
    width: 100%;
    height: 90vh;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
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
  .hero-section h1 {
    font-size: 2.75rem;
    font-weight: 700;
    color: #fff;
  }
  .hero-section p {
    font-size: 1.125rem;
    color: #fff;
  }
  .text-highlight {
    color: var(--secondary);
  }
  .search-input-group {
    border-radius: 50px;
    max-width: 600px;
    width: 100%;
    margin: 2rem auto 0;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
  }
  .search-input-group .form-control {
    border: none;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    background: #fff !important;
    color: var(--dark-text);
    border-radius: 50px 0 0 50px;
    transition: box-shadow 0.3s ease;
  }
  .search-input-group .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(40, 154, 132, 0.25);
  }
  .search-input-group .form-control::placeholder {
    color: #666;
    opacity: 1;
  }
  .search-input-group .btn {
    border: none;
    background: var(--secondary);
    color: white;
    font-size: 1.15rem;
    padding: 0 1.5rem;
    transition: background 0.3s ease, transform 0.3s ease;
  }
  .search-input-group .btn:hover {
    background: #21c493;
    transform: scale(1.05);
  }

  /* CATEGORY CARDS */
  .category-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
    padding: 1rem;
  }
  .category-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
  }
  .category-card img {
    width: 100%;
    height: 240px;
    object-fit: cover;
    border-radius: var(--radius);
    transition: transform 0.4s ease;
  }
  .category-card:hover img {
    transform: scale(1.05);
  }
  .category-card h6 {
    color: var(--primary);
    font-weight: 600;
    margin-top: 0.75rem;
    font-size: 1.1rem;
  }

  /* PROPERTY CARDS */
  .property-card {
    background: #fff;
    border-radius: var(--radius);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: var(--shadow-sm);
    border: none;
    display: flex;
    flex-direction: column;
  }
  .property-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
  }
  .property-card .card-img-top {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: transform 0.4s ease;
  }
  .property-card:hover .card-img-top {
    transform: scale(1.05);
  }
  .property-card .badge {
    font-size: 0.85rem;
    padding: 0.35rem 0.8rem;
    color: #ffffff;
  }
  .property-card .card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .property-card .card-title {
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
  }
  .property-card .text-muted {
    color: #666 !important;
    font-size: 0.9rem;
  }
  /* Harga tetap merah */
  .property-card .price {
    color: #dc3545;
    font-weight: 700;
    font-size: 1.15rem;
  }
  .property-card .star-rating i,
  .property-card .fa-star {
    color: #FFD700;
    font-size: 1rem;
  }

  /* FADE-IN ANIMATION */
  .fade-in {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
  }
  .fade-in.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* RESPONSIVE ADJUSTMENTS */
  @media (max-width: 768px) {
    .hero-section {
      height: 75vh;
      text-align: center;
    }
    .hero-section h1 {
      font-size: 2rem;
    }
    .hero-section p {
      font-size: 1rem;
    }
    .search-input-group {
      margin-top: 1.5rem;
    }
    .search-input-group .form-control {
      border-radius: 50px !important;
    }
    .search-input-group .btn {
      border-radius: 50px !important;
      margin-top: 0.5rem;
    }
    .category-card img {
      height: 200px;
    }
    .property-card .card-img-top {
      height: 150px;
    }
  }

  @media (max-width: 576px) {
    .hero-section h1 {
      font-size: 1.75rem;
    }
    .hero-section p {
      font-size: 0.95rem;
    }
    .category-card img {
      height: 180px;
    }
    .property-card .card-img-top {
      height: 130px;
    }
  }
</style>

@if(Auth::check() && Auth::user()->role_id == 3 && !Auth::user()->isPenyewaProfileComplete())
    <!-- CDN SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'warning',
          title: 'Lengkapi Profil Anda',
          html: 'Untuk menggunakan semua fitur, silakan lengkapi profil Anda.<br><br>' +
                '<a href="{{ route('profileuser.edit') }}" class="btn btn-success" style="border-radius:16px; margin-top:8px;">Lengkapi Sekarang</a>',
          showCancelButton: true,
          showConfirmButton: false,
          cancelButtonText: 'Nanti Saja',
          cancelButtonColor: '#d33',
          allowOutsideClick: false,
          customClass: {
            popup: 'rounded-4'
          }
        });
      });
    </script>
@endif


<!-- HERO CAROUSEL -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
  <!-- Indicators -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
  </div>

  <!-- Slides -->
  <div class="carousel-inner">
    <!-- Slide 1 -->
    <div class="carousel-item active">
      <section class="hero-section" style="background-image: url('/assets/images/auth/Pemandangan_Gunung_Bromo.jpg');">
        <div class="hero-overlay"></div>
        <div class="container d-flex flex-column justify-content-center align-items-center">
          <h1 class="fw-bold text-white">Hai kamu benyamin, <span class="text-highlight">Mau Cari Penginapan?</span></h1>
          <p class="lead text-white fw-semibold mt-3">Hommie – Platform terbaik buat cari kost & homestay harian!</p>
          <form action="{{ route('search_welcomeProperty') }}" method="GET" class="search-input-group mt-4">
            <div class="input-group input-group-lg">
              <input type="text" name="keyword"
                     class="form-control"
                     placeholder="Masukkan nama lokasi/area/alamat"
                     value="{{ request('keyword') }}">
              <button class="btn" type="submit"><i class="bi bi-search"></i></button>
            </div>
          </form>
        </div>
      </section>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-item">
      <section class="hero-section" style="background-image: url('/assets/images/auth/kosts.jpg');">
        <div class="hero-overlay"></div>
        <div class="container d-flex flex-column justify-content-center align-items-center">
          <h1 class="fw-bold text-white">Temukan <span class="text-highlight">Kost Impianmu</span></h1>
          <p class="lead text-white fw-semibold mt-3">Dengan lokasi strategis dan harga terjangkau</p>
          <form action="{{ route('search_welcomeProperty') }}" method="GET" class="search-input-group mt-4">
            <div class="input-group input-group-lg">
              <input type="text" name="keyword"
                     class="form-control"
                     placeholder="Masukkan nama lokasi/area/alamat"
                     value="{{ request('keyword') }}">
              <button class="btn" type="submit"><i class="bi bi-search"></i></button>
            </div>
          </form>
        </div>
      </section>
    </div>

    <!-- Slide 3 -->
    <div class="carousel-item">
      <section class="hero-section" style="background-image: url('/assets/images/auth/home.jpg');">
        <div class="hero-overlay"></div>
        <div class="container d-flex flex-column justify-content-center align-items-center">
          <h1 class="fw-bold text-white">Booking <span class="text-highlight">Homestay Favoritmu</span></h1>
          <p class="lead text-white fw-semibold mt-3">Mudah, cepat, dan banyak pilihan menarik untuk liburan & staycation!</p>
          <form action="{{ route('search_welcomeProperty') }}" method="GET" class="search-input-group mt-4">
            <div class="input-group input-group-lg">
              <input type="text" name="keyword"
                     class="form-control"
                     placeholder="Masukkan nama lokasi/area/alamat"
                     value="{{ request('keyword') }}">
              <button class="btn" type="submit"><i class="bi bi-search"></i></button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>

  <!-- Controls -->
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
  <section class="my-5 fade-in">
    <h4 class="fw-bold" style="color: var(--primary);">Hai, ada yang bisa dibantu?</h4>
    <p class="mb-4">Telusuri kategori informasi sesuai tipe akun berikut ini:</p>
    <div class="row g-4">
      <div class="col-12 col-md-6">
        <div class="category-card" role="button" data-bs-toggle="modal" data-bs-target="#modalOwner">
          <img src="/assets/images/auth/owner.jpg" alt="Pemilik Properti">
          <h6>Pemilik Properti</h6>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="category-card" role="button" data-bs-toggle="modal" data-bs-target="#modalTenant">
          <img src="/assets/images/auth/customer.jpg" alt="Calon Penyewa">
          <h6>Calon Penyewa</h6>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal: Pemilik Properti -->
  <div class="modal fade" id="modalOwner" tabindex="-1" aria-labelledby="modalOwnerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content rounded-4 shadow-lg border-0">
        <div class="modal-header bg-gradient-primary text-white rounded-top-4">
          <i class="fas fa-building fa-2x me-2"></i>
          <h5 class="modal-title fw-bold fs-4" id="modalOwnerLabel">Pemilik Properti</h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body p-4">
          <p class="fs-6 text-secondary">
            Akun ini dirancang khusus untuk <strong>pemilik kos, homestay, rumah sewa, atau apartemen</strong> yang ingin memasarkan properti mereka melalui platform Hommie. Dengan akun ini, Anda memiliki kendali penuh atas informasi dan penawaran properti Anda. Platform kami memberikan kemudahan dalam mengelola data kamar, menetapkan harga, mengunggah foto, dan memantau pemesanan secara real-time.
          </p>
          <ul class="list-unstyled ps-3 mt-3">
            <li class="mb-2 d-flex align-items-center">
              <i class="fas fa-check-circle text-success me-2"></i> Menjangkau ribuan calon penyewa dari berbagai kota
            </li>
            <li class="mb-2 d-flex align-items-center">
              <i class="fas fa-check-circle text-success me-2"></i> Mengelola properti Anda dengan dashboard yang intuitif
            </li>
            <li class="mb-2 d-flex align-items-center">
              <i class="fas fa-check-circle text-success me-2"></i> Menerima laporan pemesanan dan pembayaran otomatis
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
        <div class="modal-header bg-gradient-success text-white rounded-top-4">
          <i class="fas fa-user-check fa-2x me-2"></i>
          <h5 class="modal-title fw-bold fs-4" id="modalTenantLabel">Calon Penyewa</h5>
          <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body p-4">
          <p class="fs-6 text-secondary">
            Akun ini ditujukan untuk Anda yang sedang mencari hunian sementara seperti <strong>kos harian, kos bulanan, homestay, atau tempat tinggal untuk liburan</strong>. Dengan akun penyewa, Anda bisa mencari properti yang sesuai dengan preferensi Anda, melakukan pemesanan langsung, serta memberikan ulasan setelah menginap.
          </p>
          <ul class="list-unstyled ps-3 mt-3">
            <li class="mb-2 d-flex align-items-center">
              <i class="fas fa-search-location text-success me-2"></i> Mencari dan menyaring properti berdasarkan lokasi, harga, dan fasilitas
            </li>
            <li class="mb-2 d-flex align-items-center">
              <i class="fas fa-calendar-check text-success me-2"></i> Melakukan pemesanan dan pembayaran secara aman & cepat
            </li>
            <li class="mb-2 d-flex align-items-center">
              <i class="fas fa-comments text-success me-2"></i> Memberikan ulasan dan rating untuk membantu pengguna lain
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Properties Section -->
  <section class="my-5 fade-in">
    <h4 class="fw-bold mb-4" style="color: var(--primary);">Mau Cari Penginapan Apa?</h4>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
      @foreach ($properties as $property)
        <div class="col fade-in">
          <a href="{{ route('detail-property.show', $property->property_id) }}" class="text-decoration-none">
            <div class="card h-100 property-card">
              <!-- Badge Tipe Properti -->
              @php $type = $property->property_type; @endphp
              <span 
                class="position-absolute top-0 start-0 m-2 px-3 py-1 rounded-pill badge"
                style="
                  @if($type === 'Homestay')
                    background-color: #289A84;
                  @elseif($type === 'Kost')
                    background-color: #152C5B;
                  @else
                    background-color: #6c757d;
                  @endif
                ">
                {{ $type }}
              </span>

              <!-- Gambar Properti -->
              <div class="overflow-hidden" style="aspect-ratio: 3/2;">
                <img src="{{ asset('storage/' . $property->image) }}"
                     class="card-img-top"
                     alt="{{ $property->property_name }}">
              </div>

              <div class="card-body d-flex flex-column justify-content-between">
                <div>
                  <h6 class="card-title">{{ $property->property_name }}</h6>
                  <small class="text-muted d-block mb-1">{{ $property->alamat_selengkapnya }}</small>
                  <small class="text-muted d-block mb-2">{{ $property->district }}, {{ $property->city }}</small>

                  <!-- Rating -->
                  <div class="d-flex align-items-center mb-2">
                    @php
                      $avgRating = round($property->avg_rating, 1);
                      $fullStars = floor($avgRating);
                      $halfStar = ($avgRating - $fullStars) >= 0.5;
                    @endphp
                    <div class="me-2">
                      @for ($i = 1; $i <= $fullStars; $i++)
                        <i class="fas fa-star"></i>
                      @endfor
                      @if ($halfStar)
                        <i class="fas fa-star-half-alt"></i>
                        @php $fullStars++ @endphp
                      @endif
                      @for ($i = $fullStars + 1; $i <= 5; $i++)
                        <i class="far fa-star"></i>
                      @endfor
                    </div>
                    <span class="text-muted small">
                      {{ $property->total_reviews > 0 
                          ? number_format($avgRating, 1) . ' (' . $property->total_reviews . ')' 
                          : 'Belum ada ulasan' }}
                    </span>
                  </div>
                </div>
                <div>
                  <p class="price mb-0">IDR {{ number_format($property->min_price) }}</p>
                </div>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </section>
</div>

<!-- FADE-IN SCRIPT -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.fade-in');
    const checkFade = () => {
      const triggerBottom = window.innerHeight * 0.8;
      fadeElements.forEach(el => {
        const boxTop = el.getBoundingClientRect().top;
        if (boxTop < triggerBottom) {
          el.classList.add('visible');
        }
      });
    };
    window.addEventListener('scroll', checkFade);
    checkFade();
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var input = document.querySelector("#phone_number_penyewa");
    if (input) {
      var iti = window.intlTelInput(input, {
        initialCountry: "id",
        nationalMode: false,
        formatOnDisplay: true,
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"
      });

      // Saat form submit, ganti value dengan nomor full (kode negara)
      var form = input.closest('form');
      if (form) {
        form.addEventListener('submit', function(e) {
          input.value = iti.getNumber(); // akan jadi +62812xxxx
        });
      }
    }
  });
</script>

@endsection
