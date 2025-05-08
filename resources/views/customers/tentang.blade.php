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
        <source src="img/video background.mp4" type="video/mp4" />
    </video>
    <div class="position-absolute w-100 h-100 top-0 start-0 bg-dark opacity-50 z-n1"></div>
    <div class="container position-relative py-5 text-center text-white">
        <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Hommie</h1>
        <p class="lead mb-5 animate__animated animate__fadeInUp">Kost & Homestay Nyaman, Modern, Strategis</p>
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
                <div class="col-lg-6 animate__animated animate__fadeInLeft">
                    <p class="text-secondary">
                        Hommie hadir sebagai solusi modern untuk hunian yang nyaman dan praktis, baik bagi pelajar, pekerja, maupun wisatawan. 
                        Melalui aplikasi web kami, Anda bisa dengan mudah mencari dan memesan kost atau homestay di lokasi strategis, 
                        lengkap dengan fasilitas terbaik dan sistem pemesanan yang efisien—semuanya cukup dari genggaman tangan Anda.
                    </p>
                </div>
                <div class="col-lg-6 d-flex justify-content-center animate__animated animate__fadeInRight">
                    <img src="img/logo2.png" class="img-fluid rounded-4 shadow-sm" style="max-width: 400px;" alt="Logo Hommie">
                </div>
            </div>
        </div>
    </section>

    <!-- Kost & Homestay Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Hunian Terbaik untuk Kebutuhan Anda</h2>
                <div class="mx-auto" style="width: 50px; height: 3px; background-color: var(--primary-color);"></div>
            </div>
            <div class="row g-5">
                <!-- Kost -->
                <div class="col-lg-6 animate__animated animate__fadeInLeft">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-hover">
                        <img src="img/kost.png" class="card-img-top" alt="Kost">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-3">Kost</h3>
                            <p class="text-secondary mb-4">
                                Kost & Homestay kami dirancang untuk memenuhi kebutuhan Anda yang mencari tempat tinggal harian, mingguan, maupun bulanan.
                            </p>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-house-check text-success me-2"></i>
                                <span>Fasilitas Lengkap</span>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <i class="bi bi-shield-check text-success me-2"></i>
                                <span>Keamanan 24 Jam</span>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm rounded-pill px-4">Lihat Kost</a>
                        </div>
                    </div>
                </div>
                <!-- Homestay -->
                <div class="col-lg-6 animate__animated animate__fadeInRight">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-hover">
                        <img src="img/homestay.png" class="card-img-top" alt="Homestay">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-3">Homestay</h3>
                            <p class="text-secondary mb-4">
                                Lokasi strategis dekat kampus, pusat kota, dan fasilitas umum menjadikan kami pilihan ideal untuk keluarga atau rombongan.
                            </p>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-people text-success me-2"></i>
                                <span>Cocok untuk Keluarga</span>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <i class="bi bi-currency-dollar text-success me-2"></i>
                                <span>Harga Terjangkau</span>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm rounded-pill px-4">Lihat Homestay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Wisata Terdekat Section -->
    <section class="py-5 bg-light-white">
        <div class="container">
            <div class="text-center mb-5">
                <p class="text-uppercase text-muted mb-2">Wisata Terdekat</p>
                <h2 class="section-title">Rekomendasi Tempat Wisata</h2>
                <div class="mx-auto" style="width: 50px; height: 3px; background-color: var(--primary-color);"></div>
                <p class="text-secondary mt-3">Nikmati berbagai destinasi menarik di sekitar properti kami.</p>
            </div>
            <div class="row g-4">
                @foreach(['Pelabuhan Vanue', 'Museum TB Silalahi', 'Pusat Kuliner Bistro', 'Pantai Lumban Bul-Bul'] as $index => $wisata)
                    <div class="col-md-3 col-sm-6 animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-hover">
                            <img src="img/Venue.png" class="card-img-top" alt="{{ $wisata }}">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-2">{{ $wisata }}</h5>
                                <p class="card-text text-secondary mb-3">Tempat favorit untuk bersantai bersama keluarga.</p>
                                <div class="d-flex align-items-center text-secondary">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                    <small>{{ $index % 2 == 0 ? '1.5 km' : '2.3 km' }} dari properti</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="#" class="btn btn-primary btn-sm rounded-pill px-4">Lihat Semua Destinasi</a>
            </div>
        </div>
    </section>
</div>
<!-- CTA Section -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4 fw-bold">Siap Menikmati Kenyamanan Tinggal Bersama Kami?</h2>
        <p class="mb-5 w-75 mx-auto">
            Jangan lewatkan kesempatan untuk menikmati hunian nyaman dengan lokasi strategis dan fasilitas modern.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="#" class="btn btn-light fw-bold px-4 rounded-pill">Lihat Property</a>
            <a href="#" class="btn btn-outline-light px-4 rounded-pill">Kontak Kami</a>
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

