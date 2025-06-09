<!doctype html>
<html lang="en" data-bs-theme="">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HomMie</title>
  <!--favicon-->
  <link rel="icon" href="{{ asset('assets/images/newLogohommie.png') }}" width="100" type="image/png">

  <!-- Loader -->
  <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
  <script src="{{ asset('assets/js/pace.min.js') }}"></script>

  <!-- Plugins -->
  <link href="{{ asset('assets/plugins/OwlCarousel/css/owl.carousel.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/plugins/lightbox/dist/css/glightbox.min.css') }}">

  <!-- Bootstrap CSS -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">

  <!-- Main CSS -->
  <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/horizontal-menu.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/dark-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/semi-dark.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/blue-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/bordered-theme.css') }}" rel="stylesheet">

  {{-- nomor telepon --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var input = document.querySelector("#inputPhoneNumber");
      if (input) {
        window.intlTelInput(input, {
          initialCountry: "id",
          separateDialCode: true,
          utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
        });
      }
    });
  </script>

  <style>
    /* Gradient Theme */
    .gradient-header {
      background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
    }

    /* Base styling for nav-links */
    .navbar-nav .nav-link {
      color: #343a40;
      font-weight: 500;
      transition: color 0.2s ease, transform 0.2s ease;
    }
    .navbar-nav .nav-link:hover {
      color: #289A84 !important;
      transform: translateY(-2px);
    }

    /* Active link styling */
    .nav-link-active {
      color: #289A84 !important;
      font-weight: 600;
      border-bottom: 2px solid #289A84;
      padding-bottom: 0.25rem;
    }

    /* Dropdown Menu */
    .dropdown-menu {
      min-width: 180px;
      border-radius: 12px;
      padding: 0.5rem 0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .dropdown-item {
      border-radius: 8px;
      transition: background-color 0.2s ease, color 0.2s ease;
      color: #343a40;
    }
    .dropdown-item:hover,
    .dropdown-item:focus {
      background-color: #e6f7f1 !important;
      color: #289A84 !important;
    }
    .dropdown-item.active {
      background-color: #289A84 !important;
      color: #ffffff !important;
    }

    /* Profile Dropdown */
    .profile-header {
      background: #f0fdf4;
      border-bottom: 1px solid #e2f1e8;
    }

    /* Button Login */
    .btn-gradient-login {
      background: linear-gradient(135deg, #289A84 0%, #38a169 100%);
      color: white;
      padding: 0.4rem 1.2rem;
      border-radius: 50px;
      font-weight: 500;
      box-shadow: 0 4px 12px rgba(40, 154, 132, 0.3);
      transition: all 0.3s ease;
    }
    .btn-gradient-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(40, 154, 132, 0.4);
    }

    /* Offcanvas (Mobile) nav-link */
    .offcanvas .nav-link {
      color: #343a40;
      font-weight: 500;
      transition: color 0.2s ease, background-color 0.2s ease;
    }
    .offcanvas .nav-link:hover {
      color: #289A84 !important;
      background-color: rgba(40, 154, 132, 0.1);
    }
    .offcanvas .nav-link.nav-link-active {
      color: #ffffff !important;
      background-color: #289A84;
      border-radius: 0.375rem;
    }

    /* Offcanvas dropdown-item */
    .offcanvas .dropdown-item {
      color: #343a40;
      transition: color 0.2s ease, background-color 0.2s ease;
    }
    .offcanvas .dropdown-item:hover {
      color: #289A84 !important;
      background-color: rgba(40, 154, 132, 0.1);
    }
    .offcanvas .dropdown-item.active {
      background-color: #289A84;
      color: #ffffff !important;
    }

    /* Mobile Menu */
    .offcanvas {
      border-radius: 1rem 1rem 0 0;
      box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Logo Animation */
    .logo-img {
      transition: transform 0.3s ease;
    }
    .logo-img:hover {
      transform: rotate(2deg) scale(1.05);
    }

    /* Header styling */
    .top-header {
      background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      z-index: 1000;
    }
    .top-header.scrolled {
      background: #F1F5F9;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Notification badge */
    .badge-notify {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #ff9800;
      color: white;
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 50%;
    }

    /* Notification detail */
    .notify-title { font-size: 0.95rem; font-weight: 600; }
    .notify-desc  { font-size: 0.85rem; color: #666; }
    .notify-time  { font-size: 0.75rem; color: #999; }
    .notify-close { cursor: pointer; opacity: 0.6; transition: opacity 0.3s ease; }
    .notify-close:hover { opacity: 1; }
    .dropdown-menu-notifikasi { max-height: 350px; overflow-y: auto; }

    /* Footer styling */
    footer {
      background-color: #27445d;
      color: white;
      padding: 60px 0 30px;
    }
    footer h5,
    footer h6 {
      color: white;
      font-weight: 600;
      margin-bottom: 20px;
    }
    footer a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: color 0.3s;
    }
    footer a:hover {
      color: white;
      text-decoration: none;
    }
    /* Navbar nav list */
.navbar-nav {
  align-items: center; /* pastikan semua item align tengah */
}

/* Profil dropdown toggle */
.navbar-nav .nav-item.dropdown > a.nav-link.dropdown-toggle {
  display: flex !important;
  align-items: center !important;
  gap: 0.5rem;
  padding: 0.25rem 0.5rem; /* sesuaikan padding */
  height: 42px; /* samakan tinggi dengan icon notifikasi */
}

/* Gambar profil */
.navbar-nav .nav-item.dropdown > a.nav-link.dropdown-toggle img.rounded-circle {
  width: 38px !important;
  height: 38px !important;
  object-fit: cover;
  border: 2px solid #289A84;
}

/* Jika menggunakan inisial profil (div) */
.navbar-nav .nav-item.dropdown > a.nav-link.dropdown-toggle > div {
  width: 38px;
  height: 38px;
  font-weight: 600;
  font-size: 1rem;
  line-height: 38px;
  text-align: center;
  border-radius: 50%;
  background-color: #289A84;
  color: white;
  user-select: none;
}

/* Icon notifikasi */
.navbar-nav .nav-item.dropdown > a.nav-link .material-icons-outlined {
  line-height: 42px; /* agar vertikal center */
  font-size: 28px;
  color: #333;
}

/* Badge notifikasi */
.navbar-nav .nav-item.dropdown > a.nav-link .badge {
  top: 6px !important;
  right: 6px !important;
  font-size: 0.7rem;
  padding: 2px 6px;
}

/* Pastikan dropdown item tidak terlalu tinggi */
.dropdown-menu {
  padding: 0.5rem 0;
}

  </style>
</head>

<body>
  <!--start header-->
  @php
    // Ambil user + relasi penyewa, hanya jika user sudah login
    $user = Auth::user() ? Auth::user()->load('penyewa') : null;
  @endphp

  <header class="top-header sticky-top gradient-header" id="Parent_Scroll_Div">
      <nav class="navbar navbar-expand-lg container px-4 px-lg-0 py-2 align-items-center">
          <!-- Logo -->
          <div class="d-flex align-items-center gap-2">
              <a href="{{ route('landingpage') }}" class="d-flex align-items-center text-decoration-none mt-3">
                  <img src="{{ asset('assets/images/newLogohommie.png') }}"
                      alt="Hommie Logo"
                      width="110"
                      class="logo-img">
              </a>
          </div>

          <!-- Toggle Button (Mobile) -->
          <button class="navbar-toggler border-0 shadow-none"
                  type="button"
                  data-bs-toggle="offcanvas"
                  data-bs-target="#offcanvasNavbar"
                  aria-controls="offcanvasNavbar">
              <i class="material-icons-outlined fs-3 text-success">menu</i>
          </button>

          <!-- Navbar Items (Desktop) -->
          <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <ul class="navbar-nav align-items-center gap-4">
                  <!-- Beranda -->
                  <li class="nav-item">
                      @php $isLanding = Route::currentRouteName() == 'landingpage'; @endphp
                      <a class="nav-link fs-6 {{ $isLanding ? 'nav-link-active' : '' }}"
                          href="{{ route('landingpage') }}">
                          Beranda
                      </a>
                  </li>

                  <!-- Dropdown Property -->
                  <li class="nav-item dropdown">
                      @php $isProperty = in_array(Route::currentRouteName(), ['homestay.properties', 'kost.properties']); @endphp
                      <a class="nav-link dropdown-toggle fs-6 {{ $isProperty ? 'nav-link-active' : '' }}"
                          href="#"
                          data-bs-toggle="dropdown">
                          Property
                      </a>
                      <ul class="dropdown-menu shadow border-0 rounded-4 p-2">
                          <li>
                              @php $isHomestay = Route::currentRouteName() == 'homestay.properties'; @endphp
                              <a class="dropdown-item {{ $isHomestay ? 'active' : '' }}"
                                  href="{{ route('homestay.properties') }}">
                                  Homestay
                              </a>
                          </li>
                          <li>
                              @php $isKost = Route::currentRouteName() == 'kost.properties'; @endphp
                              <a class="dropdown-item {{ $isKost ? 'active' : '' }}"
                                  href="{{ route('kost.properties') }}">
                                  Kost
                              </a>
                          </li>
                      </ul>
                  </li>

                  <!-- Tentang -->
                  <li class="nav-item">
                      @php $isTentang = Route::currentRouteName() == 'tentang'; @endphp
                      <a class="nav-link fs-6 {{ $isTentang ? 'nav-link-active' : '' }}"
                          href="{{ route('tentang') }}">
                          Tentang
                      </a>
                  </li>

                  <!-- Jika Belum Login -->
                  @guest
                  <li class="nav-item d-flex align-items-center gap-2">
                      <a href="{{ route('login') }}"
                          class="btn btn-gradient-login d-flex align-items-center gap-2 px-4 py-2">
                          <i class="material-icons-outlined fs-6">account_circle</i> Masuk
                      </a>
                      <a href="{{ route('register') }}"
                          class="btn btn-outline-success d-flex align-items-center gap-2 px-4 py-2"
                          style="border-radius: 30px; font-weight: 600; font-size: 1rem; border: 2px solid #289A84; color: #289A84;">
                          <i class="material-icons-outlined fs-6">person_add</i> Daftar
                      </a>
                  </li>
                  @endguest

                  <!-- Jika Sudah Login -->
                  @auth
                  <!-- Notifikasi -->
                  @php $notifications = Auth::user()->unreadNotifications; @endphp
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle d-flex align-items-center gap-1"
                          href="#"
                          data-bs-toggle="dropdown">
                          <i class="material-icons-outlined fs-5">notifications</i>
                          @if($notifications->count() > 0)
                          <span class="badge bg-danger rounded-circle">{{ $notifications->count() }}</span>
                          @endif
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end shadow dropdown-menu-notifikasi p-2"
                          style="width: 300px; max-height: 350px; overflow-y: auto;">
                          @forelse ($notifications as $notification)
                          <li>
                              <a href="{{ $notification->data['link'] }}"
                                  class="dropdown-item d-flex flex-column">
                                  <span>{{ $notification->data['message'] }}</span>
                                  <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                              </a>
                          </li>
                          @empty
                          <li class="dropdown-item text-muted">Tidak ada notifikasi baru</li>
                          @endforelse
                      </ul>
                  </li>

                  <!-- Profile -->
                  <li class="nav-item dropdown">
                      <a href="#"
                          class="nav-link dropdown-toggle p-0 d-flex align-items-center gap-2"
                          data-bs-toggle="dropdown" aria-expanded="false" title="Profil">
                          @if($user && $user->avatar_url)
                              <img src="{{ $user->avatar_url }}"
                                  alt="{{ $user->name }}"
                                  class="rounded-circle border"
                                  style="width:38px; height:38px; object-fit:cover;">
                          @else
                              <div style="
                                  width:38px; height:38px;
                                  background: #289A84; color: #fff;
                                  font-weight:600; text-transform:uppercase;
                                  display:flex; align-items:center; justify-content:center;
                                  border-radius:50%;
                                  font-size:1rem;
                              ">
                                  {{ $user->initials ?? '' }}
                              </div>
                          @endif
                          <span class="d-none d-lg-inline text-dark">{{ $user->name }}</span>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 overflow-hidden">
                          <li class="text-center p-3 bg-light">
                              @if($user && $user->avatar_url)
                                  <img src="{{ $user->avatar_url }}"
                                      class="rounded-circle border mb-2"
                                      style="width:60px; height:60px; object-fit:cover;">
                              @else
                                  <div style="
                                      width:60px; height:60px; font-size:24px;
                                      background:#289A84; color:#fff;
                                      font-weight:600; border-radius:50%;
                                      display:flex; align-items:center; justify-content:center;
                                  ">
                                      {{ $user->initials ?? '' }}
                                  </div>
                              @endif
                              <h6 class="fw-semibold mb-0">{{ $user->name }}</h6>
                              <small class="text-muted">
                                  {{ $user->penyewa->email_penyewa ?? $user->email }}
                              </small>
                          </li>
                          <li><hr class="dropdown-divider m-0"></li>
                          <li>
                              <a class="dropdown-item d-flex align-items-center gap-2 py-3"
                                  href="{{ route('profileuser.show') }}">
                                  <i class="material-icons-outlined fs-6">person_outline</i> Profil
                              </a>
                          </li>
                          <li><hr class="dropdown-divider m-0"></li>
                          <li>
                              <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                                  @csrf
                                  <button type="submit"
                                          class="dropdown-item d-flex align-items-center gap-2 py-3 text-danger">
                                      <i class="material-icons-outlined fs-6">power_settings_new</i> Logout
                                  </button>
                              </form>
                          </li>
                      </ul>
                  </li>
                  @endauth
              </ul>
          </div>
      </nav>
  </header>

  <!--end top header-->

  <!-- Offcanvas untuk Mobile (Nav Menu) -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
        <!-- Beranda -->
        @php $isLandingMobile = Route::currentRouteName() == 'landingpage'; @endphp
        <li class="nav-item">
          <a class="nav-link {{ $isLandingMobile ? 'nav-link-active' : '' }}"
             href="{{ route('landingpage') }}">
            Beranda
          </a>
        </li>

        <!-- Dropdown Property (Mobile) -->
        <li class="nav-item dropdown">
          @php $isPropertyMobile = in_array(Route::currentRouteName(), ['homestay.properties', 'kost.properties']); @endphp
          <a class="nav-link dropdown-toggle {{ $isPropertyMobile ? 'nav-link-active' : '' }}"
             href="#"
             data-bs-toggle="dropdown"
             aria-expanded="false">
            Property
          </a>
          <ul class="dropdown-menu">
            <li>
              @php $isHomestayMobile = Route::currentRouteName() == 'homestay.properties'; @endphp
              <a class="dropdown-item {{ $isHomestayMobile ? 'active' : '' }}"
                 href="{{ route('homestay.properties') }}">
                Homestay
              </a>
            </li>
            <li>
              @php $isKostMobile = Route::currentRouteName() == 'kost.properties'; @endphp
              <a class="dropdown-item {{ $isKostMobile ? 'active' : '' }}"
                 href="{{ route('kost.properties') }}">
                Kost
              </a>
            </li>
          </ul>
        </li>

        <!-- Tentang -->
        @php $isTentangMobile = Route::currentRouteName() == 'tentang'; @endphp
        <li class="nav-item">
          <a class="nav-link {{ $isTentangMobile ? 'nav-link-active' : '' }}"
             href="{{ route('tentang') }}">
            Tentang
          </a>
        </li>

        @guest
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Login</a>
          </li>
        @endguest

        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
               href="#"
               data-bs-toggle="dropdown"
               aria-expanded="false">
              <img src="{{ Auth::user()->profile_picture
                           ? asset('storage/' . Auth::user()->profile_picture)
                           : asset('assets/images/avatars/default.png') }}"
                   class="rounded-circle border me-2"
                   width="30"
                   alt="Profile">
              {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="{{ route('profileuser.show') }}">Profile</a>
              </li>
              <li>
                <form id="logoutFormMobile" action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">Logout</button>
                </form>
              </li>
            </ul>
          </li>
        @endauth
      </ul>
    </div>
  </div>

  <!-- Konten Utama -->
  <main class="main-wrapper" data-bs-spy="scroll" data-bs-target="#Parent_Scroll_Div" data-bs-smooth-scroll="false" tabindex="0">
    <div class="main-content">
      @yield('content')
    </div>
  </main>

  <!--start footer -->
  <footer id="contact" class="mt-5 py-5 text-white">
    <div class="container">
      <div class="row justify-content-between">
        <!-- Info -->
        <div class="col-lg-4 col-md-6 mb-4">
          <a href="{{ route('landingpage') }}" class="d-flex align-items-center text-decoration-none">
            <img src="{{ asset('assets/images/newLogohommiee.jpg') }}"
                 alt="Hommie Logo"
                 width="120"
                 class="logo-img me-2">
          </a>
          <p class="mb-4 mt-4">
            Hunian nyaman dan strategis untuk pelajar, pekerja, dan wisatawan.
            Akses mudah ke pusat kota, kampus, dan tempat wisata.
          </p>
          <div class="social-icons d-flex gap-3">
            <a href="#"><i class="bi bi-facebook fs-4 text-light"></i></a>
            <a href="#"><i class="bi bi-instagram fs-4 text-light"></i></a>
            <a href="#"><i class="bi bi-twitter-x fs-4 text-light"></i></a>
            <a href="#"><i class="bi bi-whatsapp fs-4 text-light"></i></a>
          </div>
        </div>

        <!-- Link -->
        <div class="col-lg-3 col-md-6 mb-4">
          <h6 class="mb-4">Navigasi</h6>
          <ul class="list-unstyled">
            <li class="mb-3">
              <a href="{{ route('landingpage') }}" class="text-light text-decoration">Beranda</a>
            </li>
            <li class="mb-3">
              <a href="{{ route('homestay.properties') }}" class="text-light text-decoration-none">Properti</a>
            </li>
            <li class="mb-3">
              <a href="{{ route('tentang') }}" class="text-light text-decoration-none">Tentang Kami</a>
            </li>
            <li class="mb-3">
              <a href="#" class="text-light text-decoration-none">Kontak</a>
            </li>
            <li class="mb-3">
              <a href="#" class="text-light text-decoration-none">FAQ</a>
            </li>
          </ul>
        </div>

        <!-- Kontak -->
        <div class="col-lg-4 col-md-6 mb-4">
          <h6 class="mb-4">Hubungi Kami</h6>
          <div class="d-flex mb-4">
            <i class="bi bi-geo-alt me-3 mt-1"></i>
            <p class="mb-0">Jl. Contoh No.123, Kota Lorem</p>
          </div>
          <div class="d-flex mb-4">
            <i class="bi bi-telephone me-3 mt-1"></i>
            <p class="mb-0">(021) 123-4567</p>
          </div>
          <div class="d-flex mb-4">
            <i class="bi bi-envelope me-3 mt-1"></i>
            <p class="mb-0">info@kosthomestay.com</p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--end footer section-->

  <!--Start Back To Top Button-->
  <a href="javaScript:;" class="back-to-top"><i class="material-icons-outlined">arrow_upward</i></a>
  <!--End Back To Top Button-->

  <!--start switcher-->
  <!-- (Anda bisa menempatkan switcher jika diperlukan) -->
  <!--end switcher-->

  <!--bootstrap js-->
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

  <!--plugins-->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/OwlCarousel/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/OwlCarousel/js/owl.carousel2.thumbs.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <script src="{{ asset('assets/plugins/lightbox/dist/js/glightbox.min.js') }}"></script>
  <script>
    var lightbox = GLightbox();
    lightbox.on('open', (target) => {
      console.log('lightbox opened');
    });
    var lightboxDescription = GLightbox({
      selector: '.glightbox2'
    });
    var lightboxVideo = GLightbox({
      selector: '.glightbox3'
    });
    lightboxVideo.on('slide_changed', ({ prev, current }) => {
      console.log('Prev slide', prev);
      console.log('Current slide', current);

      const { slideIndex, slideNode, slideConfig, player } = current;
      if (player) {
        if (!player.ready) {
          player.on('ready', (event) => {
            // Do something when video is ready
          });
        }
        player.on('play', (event) => {
          console.log('Started play');
        });
        player.on('volumechange', (event) => {
          console.log('Volume change');
        });
        player.on('ended', (event) => {
          console.log('Video ended');
        });
      }
    });
    var lightboxInlineIframe = GLightbox({
      selector: '.glightbox4'
    });
  </script>

  <script>
    $('.clients-shops').owlCarousel({
      loop: true,
      margin: 24,
      responsiveClass: true,
      nav: false,
      navText: [
        "<i class='bx bx-chevron-left'></i>",
        "<i class='bx bx-chevron-right'></i>"
      ],
      autoplay: true,
      autoplayTimeout: 3000,
      dots: false,
      responsive: {
        0: {
          nav: false,
          items: 1
        },
        576: {
          nav: false,
          items: 2
        },
        768: {
          nav: false,
          items: 3
        },
        1024: {
          nav: false,
          items: 3
        },
        1366: {
          items: 4
        },
        1400: {
          items: 5
        }
      },
    });
  </script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const logoutForm = document.getElementById('logoutForm');
      if (logoutForm) {
        logoutForm.addEventListener('submit', function (e) {
          e.preventDefault();
          Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan keluar dari akun ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              logoutForm.submit();
            }
          });
        });
      }
    });
  </script>

  <!-- Notification Script -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const notificationDropdown = document.querySelector('.dropdown-notify');
      const badgeNotify = document.querySelector('.badge-notify');

      axios.get('/notifications')
        .then(response => {
          const data = response.data;
          if (badgeNotify) {
            badgeNotify.textContent = data.count > 0 ? data.count : '';
            badgeNotify.style.display = data.count > 0 ? 'inline-block' : 'none';
          }
          const notifyList = notificationDropdown.querySelector('.notify-list');
          notifyList.innerHTML = '';
          if (data.count === 0) {
            notifyList.innerHTML = `
              <div class="text-center py-4 text-muted">
                <i class="bi bi-chat-left-text fs-4"></i>
                <p class="mb-0 mt-2">Tidak ada ulasan yang perlu diberikan</p>
              </div>
            `;
            return;
          }
          data.notifications.forEach(notification => {
            const item = document.createElement('div');
            item.className = 'notify-item';
            item.innerHTML = `
              <a class="dropdown-item border-bottom py-2" href="${notification.link}">
                <div class="d-flex align-items-center gap-3">
                  <div class="user-wrapper bg-success text-success bg-opacity-10">
                    <i class="bi bi-chat-left-text fs-5"></i>
                  </div>
                  <div class="flex-grow-1">
                    <h5 class="notify-title">Berikan Ulasan</h5>
                    <p class="mb-0 notify-desc">Anda bisa memberikan ulasan untuk ${notification.property_name}.</p>
                    <p class="mb-0 notify-time">${notification.time_ago}</p>
                  </div>
                  <div class="notify-close position-absolute end-0 me-3">
                    <i class="material-icons-outlined fs-6">close</i>
                  </div>
                </div>
              </a>
            `;
            notifyList.appendChild(item);
          });
          const closeButtons = notificationDropdown.querySelectorAll('.notify-close');
          closeButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
              e.preventDefault();
              const item = this.closest('.notify-item');
              if (item) item.remove();
            });
          });
        })
        .catch(error => {
          console.error('Gagal mengambil notifikasi:', error);
        });
    });
  </script>

</body>
</html>
