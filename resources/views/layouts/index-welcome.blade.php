<!doctype html>
<html lang="en" data-bs-theme="">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HomMie</title>
  <!--favicon-->
<!-- Icon -->
<link rel="icon" href="{{ asset('assets/images/hommielogo-preview.png') }}" width="100" type="image/png">

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

  <!--start header-->
  <header class="top-header sticky-top shadow-sm" id="Parent_Scroll_Div">
    <nav class="navbar navbar-expand-lg container px-4 px-lg-0 py-2 align-items-center">
      <!-- Logo dan Nama -->
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('landingpage') }}" class="d-flex align-items-center text-decoration-none">
          <img src="{{ asset('assets/images/hommielogo-preview.png') }}" 
               alt="Hommie Logo" 
               width="100" 
               class="logo-img me-2">
          <h5 class="mb-0 logo-text">
            <h5 class="mb-0" style="color:#289A84;"><span style="color:#152C5B;">Hom</span >mie
          </h5>
        </a>
      </div>
  
      <!-- Toggle Button (Mobile) -->
      <button class="navbar-toggler border-0 shadow-none" 
              type="button" 
              data-bs-toggle="offcanvas" 
              data-bs-target="#offcanvasNavbar"
              aria-controls="offcanvasNavbar">
        <i class="material-icons-outlined fs-3 text-primary">menu</i>
      </button>
  
      <!-- Navbar Menu (Desktop) -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center gap-4">
          <!-- Beranda -->
          <li class="nav-item">
            <a class="{{ Route::currentRouteName() == 'landingpage' ? 'nav-link fw-bold text-success' : 'nav-link fw-semibold text-dark' }} "
               href="{{ route('landingpage') }}">Beranda</a>
          </li>
  
          <!-- Dropdown Property -->
          <li class="nav-item dropdown">
            <a class="{{ in_array(Route::currentRouteName(), ['homestay.properties', 'kost.properties']) ? 'nav-link dropdown-toggle d-flex align-items-center gap-1 fw-bold text-success' : 'nav-link dropdown-toggle d-flex align-items-center gap-1 fw-semibold text-dark' }}" 
               href="#" 
               data-bs-toggle="dropdown"
               data-bs-auto-close="outside">
              <i class="material-icons-outlined fs-6 me-1">home</i> Property
            </a>
            <ul class="dropdown-menu shadow border-0 rounded-4 p-2">
              <li><a class="dropdown-item rounded-3 {{ Route::currentRouteName() == 'homestay.properties' ? 'active bg-success-subtle text-success' : '' }}" 
                     href="{{ route('homestay.properties') }}">Homestay</a></li>
              <li><a class="dropdown-item rounded-3 {{ Route::currentRouteName() == 'kost.properties' ? 'active bg-success-subtle text-success' : '' }}" 
                     href="{{ route('kost.properties') }}">Kost</a></li>
            </ul>
          </li>
  
          <!-- Tentang -->
          <li class="nav-item">
            <a class="{{ Route::currentRouteName() == 'tentang' ? 'nav-link fw-bold text-success' : 'nav-link fw-semibold text-dark' }}"
               href="#">Tentang</a>
          </li>
  
          <!-- Auth Links -->
          @guest
            <li class="nav-item">
              <a href="{{ route('login') }}" 
                 class="btn btn-success px-4 py-2 rounded-pill fw-medium shadow-sm d-flex align-items-center gap-2">
                <i class="material-icons-outlined fs-6">account_circle</i> Login
              </a>
            </li>
          @endguest
  
          @auth
            <li class="nav-item dropdown">
              <a href="#" 
                 class="dropdown-toggle d-flex align-items-center gap-2 text-decoration-none" 
                 data-bs-toggle="dropdown"
                 aria-expanded="false">
                <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('assets/images/avatars/default.png') }}" 
                     class="rounded-circle border p-1" 
                     width="40" height="40" 
                     alt="Profile">
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 overflow-hidden">
                <li class="text-center p-3 bg-light">
                  <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('assets/images/avatars/default.png') }}" 
                       class="rounded-circle border p-1 mb-2" 
                       width="60" height="60" 
                       alt="Profile">
                  <h6 class="fw-semibold mb-0">{{ Auth::user()->name }}</h6>
                  <small class="text-muted">{{ Auth::user()->email }}</small>
                </li>
                <li><hr class="dropdown-divider m-0"></li>
                <li>
                  <a class="dropdown-item d-flex align-items-center gap-2 py-3" href="#">
                    <i class="material-icons-outlined fs-6">person_outline</i> Profile
                  </a>
                </li>
                <li><hr class="dropdown-divider m-0"></li>
                <li>
                  <form action="{{ route('logout') }}" method="POST">
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
  <button class="btn btn-grd btn-grd-danger btn-switcher position-fixed top-50 d-flex align-items-center gap-2"
    type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop">
    <i class="material-icons-outlined">tune</i>Customize
  </button>

  <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="staticBackdrop">
    <div class="offcanvas-header border-bottom h-70">
      <div class="">
        <h5 class="mb-0">Theme Customizer</h5>
        <p class="mb-0">Customize your theme</p>
      </div>
      <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="offcanvas">
        <i class="material-icons-outlined">close</i>
      </a>
    </div>
    <div class="offcanvas-body">
      <div>
        <p>Theme variation</p>

        <div class="row g-3">
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="BlueTheme" checked>
            <label
              class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4"
              for="BlueTheme">
              <span class="material-icons-outlined">contactless</span>
              <span>Blue</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="LightTheme">
            <label
              class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4"
              for="LightTheme">
              <span class="material-icons-outlined">light_mode</span>
              <span>Light</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="DarkTheme">
            <label
              class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4"
              for="DarkTheme">
              <span class="material-icons-outlined">dark_mode</span>
              <span>Dark</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="SemiDarkTheme">
            <label
              class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4"
              for="SemiDarkTheme">
              <span class="material-icons-outlined">contrast</span>
              <span>Semi Dark</span>
            </label>
          </div>
          <div class="col-12 col-xl-6">
            <input type="radio" class="btn-check" name="theme-options" id="BoderedTheme">
            <label
              class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4"
              for="BoderedTheme">
              <span class="material-icons-outlined">border_style</span>
              <span>Bordered</span>
            </label>
          </div>
        </div><!--end row-->

      </div>
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

</body>
</html>
