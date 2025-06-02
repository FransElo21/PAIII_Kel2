@extends('layouts.index-welcome')
@section('content')

<style>
    /* Custom CSS */
    :root {
        --primary-color: #289A84;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #152C5B, #289A84);
    }

    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .animate__fadeInLeft {
        animation: fadeInLeft 1s ease-out;
    }

    .animate__fadeInRight {
        animation: fadeInRight 1s ease-out;
    }

    .animate__fadeInUp {
        animation: fadeInUp 1s ease-out;
    }
</style>

<!-- Hero Section dengan Video Background -->
<header class="position-relative overflow-hidden" style="min-height: 100vh;">
    <video autoplay muted loop playsinline class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover z-n1">
        <source src="assets/images/video background.mp4" type="video/mp4" />
    </video>
    <div class="position-absolute w-100 h-100 top-0 start-0 bg-dark opacity-50 z-n1"></div>

    <!-- Kontainer teks di tengah -->
    <div class="container position-absolute top-50 start-50 translate-middle text-center text-white">
        <h1 class="display-4 fw-bold mb-4 text-white animate_animated animate_fadeInDown">Hommie</h1>
        <p class="lead mb-5 animate_animated animate_fadeInUp">Kami menyediakan Kost & Homestay yang nyaman, modern, dan strategis.</p>
    </div>
</header>

<div class="container">
    <!-- Tentang Kami Section -->
    <section class="py-5 bg-light-primary">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-uppercase">Tentang Kami</h2>
                <div class="mx-auto" style="width: 50px; height: 3px; background-color: var(--primary-color);"></div>
            </div>
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 animate_animated animate_fadeInLeft">
                    <p class="text-secondary">
                        Hommie hadir sebagai solusi modern untuk hunian yang nyaman dan praktis, baik bagi pelajar, pekerja, maupun wisatawan. 
                        Melalui aplikasi web kami, Anda bisa dengan mudah mencari dan memesan kost atau homestay di lokasi strategis, 
                        lengkap dengan fasilitas terbaik dan sistem pemesanan yang efisien—semuanya cukup dari genggaman tangan Anda.
                    </p>
                </div>
                <div class="col-lg-6 d-flex justify-content-center animate_animated animate_fadeInRight">
                    <img src="assets/images/newLogohommiee.jpg" class="img-fluid rounded-4 shadow-sm" style="max-width: 400px;" alt="Logo Hommie">
                </div>
            </div>
        </div>
    </section>

   <!-- Kost Section -->
<section class="section bg-white py-5" id="property">
  <div class="container">
    <div class="row mb-5 text-center">
      <div class="col-12">
        <h2 class="section-title fw-bold mb-2">Hunian Terbaik untuk Kebutuhan Anda</h2>
        <div class="mx-auto" style="width: 60px; height: 4px; background-color: var(--primary-color);"></div>
      </div>
    </div>

    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <img src="assets/images/kost.png" class="img-fluid rounded shadow" alt="Kost dan Homestay" />
      </div>
      <div class="col-lg-6">
        <h2 class="fs-1 fw-bold mb-4">Kost</h2>
        <p class="text-secondary mb-4">
          Kost & Homestay kami dirancang untuk memenuhi kebutuhan Anda yang
          mencari tempat tinggal harian, mingguan, maupun bulanan dengan
          fasilitas yang lengkap dan modern.
        </p>

        <div class="d-flex mb-4 gap-3">
          <div><i class="bi bi-house-check fs-3 text-primary"></i></div>
          <div>
            <h5 class="fw-bold mb-1">Fasilitas Lengkap</h5>
            <p class="text-secondary mb-0">
              Kamar dilengkapi tempat tidur nyaman, meja kerja, lemari, dan akses internet.
            </p>
          </div>
        </div>

        <div class="d-flex mb-4 gap-3">
          <div><i class="bi bi-shield-check fs-3 text-primary"></i></div>
          <div>
            <h5 class="fw-bold mb-1">Keamanan 24 Jam</h5>
            <p class="text-secondary mb-0">
              Sistem pengawasan dan akses kontrol membuat Anda merasa aman.
            </p>
          </div>
        </div>

        <a href="{{ route('kost.properties') }}" class="btn btn-primary">Lihat Kost</a>
      </div>
    </div>
  </div>
</section>

<!-- Homestay Section -->
<section class="py-5">
  <div class="container">
    <div class="row g-0 align-items-center flex-lg-row-reverse">
      <div class="col-lg-6">
        <img src="{{ asset('assets/images/homestay.png') }}" class="img-fluid w-100" alt="Fasilitas Homestay" />
      </div>
      <div class="col-lg-6 p-5">
        <h2 class="fs-1 fw-bold mb-4">Homestay</h2>
        <p class="text-secondary mb-4">
          Kami memahami pentingnya kenyamanan dan efisiensi bagi pelajar, pekerja, maupun wisatawan.
          Lokasi strategis dekat kampus, pusat kota, dan fasilitas umum menjadikan kami pilihan ideal.
        </p>

        <div class="d-flex mb-4 gap-3">
          <div><i class="bi bi-people fs-3 text-primary"></i></div>
          <div>
            <h5 class="fw-bold mb-1">Cocok untuk Keluarga</h5>
            <p class="text-secondary mb-0">
              Dapur bersama dan area santai untuk keluarga atau rombongan.
            </p>
          </div>
        </div>

        <div class="d-flex mb-4 gap-3">
          <div><i class="bi bi-currency-dollar fs-3 text-primary"></i></div>
          <div>
            <h5 class="fw-bold mb-1">Harga Terjangkau</h5>
            <p class="text-secondary mb-0">
              Pelayanan ramah, harga terjangkau, dan suasana tenang.
            </p>
          </div>
        </div>

        <a href="{{ route('homestay.properties') }}" class="btn btn-primary">Lihat Homestay</a>
      </div>
    </div>
  </div>
</section>



    <!-- Wisata Terdekat Section -->
    <section class="py-5 bg-light-white">
        <div class="container">
            <div class="text-center mb-5    ">
                <p class="text-uppercase text-muted mb-2">Wisata Terdekat</p>
                <h2 class="fw-bold section-title">Rekomendasi Tempat Wisata</h2>
                <div class="mx-auto" style="width: 50px; height: 3px; background-color: var(--primary-color);"></div>
                <p class="text-secondary mt-3">Nikmati berbagai destinasi menarik di sekitar properti kami yang
              dapat Anda kunjungi saat menginap.</p>
            </div>
             <div class="row g-4">
          <div class="col-md-3 col-sm-6">
            <div class="card h-100 sr-fade-up">
              <img src="assets/images/Venue.png" class="card-img-top" alt="Wisata 1" />
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div
                    class="feature-icon-box me-2"
                    style="min-width: 36px; height: 36px"
                  >
                    <i class="bi bi-tree"></i>
                  </div>
                  <h5 class="card-title mb-0 fw-bold">Pelabuhan Vanue</h5>
                </div>
                <p class="card-text text-secondary mb-3">
                  Tempat favorit untuk jogging pagi dan bersantai bersama
                  keluarga.
                </p>
                <div class="d-flex align-items-center">
                  <i class="bi bi-geo-alt text-primary me-2"></i>
                  <small class="text-secondary">1.5 km dari properti</small>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card h-100 sr-fade-up">
              <img
                src="assets/images/TB Silalahi.png"
                class="card-img-top"
                alt="Wisata 2"
              />
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div
                    class="feature-icon-box me-2"
                    style="min-width: 36px; height: 36px"
                  >
                    <i class="bi bi-bank"></i>
                  </div>
                  <h5 class="card-title mb-0 fw-bold">Museum TB Silalahi</h5>
                </div>
                <p class="card-text text-secondary mb-3">
                  Pelajari kekayaan budaya lokal hanya 10 menit dari lokasi
                  kami.
                </p>
                <div class="d-flex align-items-center">
                  <i class="bi bi-geo-alt text-primary me-2"></i>
                  <small class="text-secondary">2.3 km dari properti</small>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card h-100 sr-fade-up">
              <img src="assets/images/Bistro.png" class="card-img-top" alt="Wisata 3" />
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div
                    class="feature-icon-box me-2"
                    style="min-width: 36px; height: 36px"
                  >
                    <i class="bi bi-cup-hot"></i>
                  </div>
                  <h5 class="card-title mb-0 fw-bold">Pusat Kuliner Bistro</h5>
                </div>
                <p class="card-text text-secondary mb-3">
                  Nikmati makanan khas daerah di tempat kuliner terkenal kota.
                </p>
                <div class="d-flex align-items-center">
                  <i class="bi bi-geo-alt text-primary me-2"></i>
                  <small class="text-secondary">0.8 km dari properti</small>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card h-100 sr-fade-up">
              <img src="assets/images/Pantai.png" class="card-img-top" alt="Wisata 4" />
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div
                    class="feature-icon-box me-2"
                    style="min-width: 36px; height: 36px"
                  >
                    <i class="bi bi-water"></i>
                  </div>
                  <h5 class="card-title mb-0 fw-bold">Pantai Lumban Bul-Bul</h5>
                </div>
                <p class="card-text text-secondary mb-3">
                  Destinasi wisata alam yang hanya 15 menit dari properti kami.
                </p>
                <div class="d-flex align-items-center">
                  <i class="bi bi-geo-alt text-primary me-2"></i>
                  <small class="text-secondary">5.2 km dari properti</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
            </div>
        </div>
    </section>
</div>
<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4 fw-bold text-white">Siap Menikmati Kenyamanan Tinggal Bersama Kami?</h2>
        <p class="mb-5 w-75 mx-auto">
            Jangan lewatkan kesempatan untuk menikmati hunian nyaman dengan lokasi strategis dan fasilitas modern.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://wa.me/6281397061310" 
            class="btn btn-outline-light bg-primary px-4 rounded-pill" 
            target="_blank" 
            rel="noopener">
            Kontak Kami
            </a>
        </div>
    </div>
</section>

<script>
    // AOS Animation (Optional)
    AOS.init({
        duration: 1000,
        once: true
    });
</script>
@endsection