<!doctype html>
<html lang="en" data-bs-theme="">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HomMie</title>
  <!--favicon-->
<!-- Icon -->
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
    var iti = window.intlTelInput(input, {
        initialCountry: "id",
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });
});
</script>

</head>

<body>

  <style>
    /* Gradient Theme */
    .gradient-header {
        background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
    }

    /* Nav Link Hover Effect */
    .navbar-nav .nav-link {
        transition: color 0.3s ease, transform 0.2s ease;
    }
    .navbar-nav .nav-link:hover {
        color: #289A84 !important;
        transform: translateY(-2px);
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
        transition: background-color 0.3s ease;
    }
    .dropdown-item:hover, .dropdown-item:focus {
        background-color: #e6f7f1 !important;
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
    /* Style default header */
    .top-header {
        background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        z-index: 1000;
    }

    /* Style header saat di-scroll */
    .top-header.scrolled {
        background: #F1F5F9;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .top-header {
        transition: background 0.3s ease, box-shadow 0.3s ease;
    }
  </style>

  <style>
    /* Badge Notifikasi */
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

    /* Wrapper Ikon Notifikasi */
    .user-wrapper {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.85rem;
    }

    /* Judul Notifikasi */
    .notify-title {
        font-size: 0.95rem;
        font-weight: 600;
    }

    /* Deskripsi Notifikasi */
    .notify-desc {
        font-size: 0.85rem;
        color: #666;
    }

    /* Waktu Notifikasi */
    .notify-time {
        font-size: 0.75rem;
        color: #999;
    }

    /* Efek Hover untuk Item Notifikasi */
    .dropdown-item:hover {
        background-color: #f8f9fa !important;
    }

    /* Tombol Tutup Notifikasi */
    .notify-close {
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }
    .notify-close:hover {
        opacity: 1;
    }
    .dropdown-menu-notifikasi {
        max-height: 350px;
        overflow-y: auto;
    }
  </style>

  <!--start header-->
  <header class="top-header sticky-top gradient-header" id="Parent_Scroll_Div">
    <nav class="navbar navbar-expand-lg container px-4 px-lg-0 py-2 align-items-center">
      <!-- Logo -->
      <div class="d-flex align-items-center gap-2">
          <a href="{{ route('landingpage') }}" class="d-flex align-items-center text-decoration-none">
              <img src="{{ asset('assets/images/newLogohommie.png') }}" 
                  alt="Hommie Logo" 
                  width="100" 
                  class="logo-img me-2 mt-3">
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

      <!-- Navbar Items -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <ul class="navbar-nav align-items-center gap-4">
              <!-- Beranda -->
              <li class="nav-item">
                  <a class="{{ Route::currentRouteName() == 'landingpage' ? 'nav-link fw-bold text-success' : 'nav-link fw-semibold text-dark' }}"
                    href="{{ route('landingpage') }}">Beranda</a>
              </li>

              <!-- Dropdown Property -->
              <li class="nav-item dropdown">
                  <a class="{{ in_array(Route::currentRouteName(), ['homestay.properties', 'kost.properties']) ? 'nav-link dropdown-toggle fw-bold text-success' : 'nav-link dropdown-toggle fw-semibold text-dark' }}"
                    href="#" data-bs-toggle="dropdown">
                      <i class="material-icons-outlined fs-6 me-1">home</i> Property
                  </a>
                  <ul class="dropdown-menu shadow border-0 rounded-4 p-2">
                      <li><a class="dropdown-item {{ Route::currentRouteName() == 'homestay.properties' ? 'active bg-success-subtle text-success' : '' }}" href="{{ route('homestay.properties') }}">Homestay</a></li>
                      <li><a class="dropdown-item {{ Route::currentRouteName() == 'kost.properties' ? 'active bg-success-subtle text-success' : '' }}" href="{{ route('kost.properties') }}">Kost</a></li>
                  </ul>
              </li>

              <!-- Tentang -->
              <li class="nav-item">
                  <a class="{{ Route::currentRouteName() == 'tentang' ? 'nav-link fw-bold text-success' : 'nav-link fw-semibold text-dark' }}"
                    href="{{ route('tentang') }}">Tentang</a>
              </li>

              <!-- Jika Belum Login -->
              @guest
                  <li class="nav-item">
                      <a href="{{ route('login') }}" 
                        class="btn btn-gradient-login px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                          <i class="material-icons-outlined fs-6">account_circle</i> Login
                      </a>
                  </li>
              @endguest

              <!-- Jika Sudah Login -->
              @auth
                  <!-- Notifikasi -->
                  @php
                      $notifications = Auth::user()->unreadNotifications;
                  @endphp
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown">
                          <i class="material-icons-outlined fs-5">notifications</i>
                          @if($notifications->count() > 0)
                              <span class="badge bg-danger rounded-circle">{{ $notifications->count() }}</span>
                          @endif
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end shadow dropdown-menu-notifikasi p-2" style="width: 300px; max-height: 350px; overflow-y: auto;">
                          @forelse ($notifications as $notification)
                              <li>
                                  <a href="{{ $notification->data['link'] }}" class="dropdown-item d-flex flex-column">
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
                      <a href="#" class="dropdown-toggle d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown">
                          <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('assets/images/avatars/default.png') }}" 
                              class="rounded-circle border p-1" 
                              width="40" height="40" 
                              alt="Profile">
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 overflow-hidden">
                          <li class="text-center p-3 profile-header">
                              <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('assets/images/avatars/default.png') }}" 
                                  class="rounded-circle border p-1 mb-2" 
                                  width="60" height="60" 
                                  alt="Profile">
                              <h6 class="fw-semibold mb-0">{{ Auth::user()->name }}</h6>
                              <small class="text-muted">{{ Auth::user()->email }}</small>
                          </li>
                          <li><hr class="dropdown-divider m-0"></li>
                          <li>
                              <a class="dropdown-item d-flex align-items-center gap-2 py-3" href="{{ route('profileuser.show') }}">
                                  <i class="material-icons-outlined fs-6">person_outline</i> Profile
                              </a>
                          </li>
                          <li><hr class="dropdown-divider m-0"></li>
                          <li>
                              <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                                  @csrf
                                  <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-3 text-danger">
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

  <!-- CSS untuk Animasi -->
  <style>
    .animate-modal {
        transform: scale(0.7);
        opacity: 0;
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    }
    
    .modal.show .animate-modal {
        transform: scale(1);
        opacity: 1;
    }

    .card-img-top {
      width: 100%;
      height: 200px; /* Sesuaikan tinggi */
      object-fit: cover;
    }
  </style>

  <!--start main wrapper-->
  <main class="main-wrapper" data-bs-spy="scroll" data-bs-target="#Parent_Scroll_Div" data-bs-smooth-scroll="false" tabindex="0">
    <div class="main-content pt-4">
      @yield('content')
    </div>
  </main>

  <!--end main wrapper-->


  <!--start footer -->
  <section class="page-footer mt-5 py-5">
    <div class="container py-4 px-4 px-lg-0">
      <div class="row g-4">
        <div class="col-12 col-xl-4">
          <div class="footer-widget-1">
            <div class="footer-logo mb-4">
              {{-- <img src="assets/images/logo1.png" width="160" alt=""> --}}
            </div>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
              Explicabo voluptatem mollitia et repellat qui dolorum quasi.</p>
            <p class="mb-2"><strong>Address: </strong>B895 Sudan Street,<br> United Kingdom, Pin 569874</p>
            <p class="mb-2"><strong>Phone: </strong>+01-854-256-49</p>
            <p class="mb-0"><strong>Email: </strong>info@example.com</p>
            <div class="play-store-images d-flex align-items-center gap-3 mt-4">
               <a href="javascript:;">
                 <img src="assets/images/google-play-store.png" width="160" alt="">
               </a>
               <a href="javascript:;">
                <img src="assets/images/apple-store.png" width="160" alt="">
              </a>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-2">
          <div class="footer-widget-2">
            <div class="footer-links">
              <h5 class="mb-4">Useful Links</h5>
              <div class="d-flex flex-column gap-2">
                <a href="javascript:;">Home</a>
                <a href="javascript:;">About us</a>
                <a href="javascript:;">Services</a>
                <a href="javascript:;">Portfolio</a>
                <a href="javascript:;">Contact</a>
                <a href="javascript:;">Terms of service</a>
                <a href="javascript:;">Privacy policy</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-2">
          <div class="footer-widget-3">
            <div class="footer-links">
              <h5 class="mb-4">Our Services</h5>
              <div class="d-flex flex-column gap-2">
                <a href="javascript:;">Product Development</a>
                <a href="javascript:;">Graphic Design</a>
                <a href="javascript:;">Human resourse</a>
                <a href="javascript:;">Software Developer</a>
                <a href="javascript:;">Web Design</a>
                <a href="javascript:;">CRM Management</a>
                <a href="javascript:;">eCommerce website</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-4">
          <div class="footer-widget-4">
            <h5 class="mb-4">Our Newsletter</h5>
            <div class="d-flex flex-column gap-2">
              <p>Join our newsletter to get the most recent information about our goods and services!</p>
              <form>
                <div class="input-group subscribe-control">
                  <input type="text" class="form-control">
                  <button class="btn btn-grd btn-grd-primary px-4" type="button">Subscribe</button>
                </div>
              </form>
            </div>
            <h6 class="mb-3 mt-4">Follow Us</h6>
            <div class="d-flex align-items-center justify-content-start gap-3">
              <a href="javascript:;"
                class="wh-42 bg-grd-deep-blue text-white rounded-circle d-flex align-items-center justify-content-center"><i
                  class="bi bi-linkedin fs-5"></i></a>
              <a href="javascript:;"
                class="wh-42 bg-grd-info text-white rounded-circle d-flex align-items-center justify-content-center"><i
                  class="bi bi-facebook fs-5"></i></a>
              <a href="javascript:;"
                class="wh-42 bg-grd-danger text-white rounded-circle d-flex align-items-center justify-content-center"><i
                  class="bi bi-youtube fs-5"></i></a>
              <a href="javascript:;"
                class="wh-42 bg-grd-voilet text-white rounded-circle d-flex align-items-center justify-content-center"><i
                  class="bi bi-twitter-x fs-5"></i></a>
            </div>
          </div>
        </div>
  
      </div><!--end row-->
    </div>
  </section>
  <!--end footer section-->


  <!--start footer strip-->
   <footer class="footer-strip py-3 px-4 px-lg-0 text-center border-top">
     <p class="mb-0">© 2024. www.codervent.com. | All rights reserved.</p>
   </footer>
  <!--end footer strip-->


  <!--Start Back To Top Button-->
     <a href="javaScript:;" class="back-to-top"><i class="material-icons-outlined">arrow_upward</i></a>
  <!--End Back To Top Button-->

  <!--start switcher-->
  {{-- <button class="btn btn-grd btn-grd-danger btn-switcher position-fixed top-50 d-flex align-items-center gap-2"
    type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop">
    <i class="material-icons-outlined">tune</i>Customize
  </button> --}}

  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('landingpage') }}">Beranda</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Property</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('homestay.properties') }}">Homestay</a></li>
                    <li><a class="dropdown-item" href="{{ route('kost.properties') }}">Kost</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tentang') }}">Tentang</a>
            </li>
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
            @endguest
        </ul>
    </div>
  </div>
  <!--start switcher-->

  <!--bootstrap js-->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <!--plugins-->
  <script src="assets/plugins/OwlCarousel/js/owl.carousel.min.js"></script>
  <script src="assets/plugins/OwlCarousel/js/owl.carousel2.thumbs.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script src="assets/plugins/lightbox/dist/js/glightbox.min.js"></script>
  
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
          // If player is not ready
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
        "<i class='bx bx-chevron-right' ></i>"
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
    })

  </script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutForm = document.getElementById('logoutForm');

        logoutForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Cegah pengiriman form langsung

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
                    logoutForm.submit(); // Kirim form jika konfirmasi ya
                }
            });
        });
    });
</script>
<!-- SweetAlert2 Alert -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ e(session('success')) }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

<!-- Offcanvas for Mobile -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
     aria-labelledby="offcanvasNavbarLabel">
  <div class="offcanvas-header border-bottom h-70">
    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
      <li class="nav-item">
        <a class="nav-link fw-semibold" href="{{ route('landingpage') }}">Beranda</a>
      </li>

      <!-- Dropdown Property -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">
          Property
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{ route('homestay.properties') }}">Homestay</a></li>
          <li><a class="dropdown-item" href="{{ route('kost.properties') }}">Kost</a></li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link fw-semibold" href="{{ route('tentang') }}">Tentang</a>
      </li>

      @guest
        <li class="nav-item">
          <a class="nav-link fw-semibold" href="{{ route('login') }}">Login</a>
        </li>
      @endguest

      @auth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-semibold d-flex align-items-center gap-2" href="#"
             data-bs-toggle="dropdown">
            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('assets/images/avatars/default.png') }}"
                 class="rounded-circle border" width="30" alt="Profile">
            {{ Auth::user()->name }}
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('profileuser.show') }}">Profile</a></li>
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

<script>
  window.addEventListener("scroll", function () {
    const header = document.querySelector(".top-header");
    if (window.scrollY > 50) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  });
</script>


{{-- Notification --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js "></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notificationDropdown = document.querySelector('.dropdown-notify');
        const badgeNotify = document.querySelector('.badge-notify');

        axios.get('/notifications')
            .then(response => {
                const data = response.data;

                // Update badge jumlah notifikasi
                if (badgeNotify) {
                    badgeNotify.textContent = data.count > 0 ? data.count : '';
                    badgeNotify.style.display = data.count > 0 ? 'inline-block' : 'none';
                }

                // Kosongkan daftar notifikasi
                const notifyList = notificationDropdown.querySelector('.notify-list');
                notifyList.innerHTML = '';

                // Jika tidak ada notifikasi
                if (data.count === 0) {
                    notifyList.innerHTML = `
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-chat-left-text fs-4"></i>
                            <p class="mb-0 mt-2">Tidak ada ulasan yang perlu diberikan</p>
                        </div>
                    `;
                    return;
                }

                // Tampilkan notifikasi
                data.notifications.forEach(notification => {
                    const item = document.createElement('div');
                    item.className = 'notify-item';

                    item.innerHTML = `
                        <a class="dropdown-item border-bottom py-2" href="${notification.link}">
                            <div class="d-flex align-items-center gap-3">
                                <!-- Ikon -->
                                <div class="user-wrapper bg-success text-success bg-opacity-10">
                                    <i class="bi bi-chat-left-text fs-5"></i>
                                </div>

                                <!-- Isi Notifikasi -->
                                <div class="flex-grow-1">
                                    <h5 class="notify-title">Berikan Ulasan</h5>
                                    <p class="mb-0 notify-desc">Anda bisa memberikan ulasan untuk ${notification.property_name}.</p>
                                    <p class="mb-0 notify-time">${notification.time_ago}</p>
                                </div>

                                <!-- Tombol Tutup -->
                                <div class="notify-close position-absolute end-0 me-3">
                                    <i class="material-icons-outlined fs-6">close</i>
                                </div>
                            </div>
                        </a>
                    `;

                    notifyList.appendChild(item);
                });

                // Tambahkan event listener untuk tombol tutup
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
