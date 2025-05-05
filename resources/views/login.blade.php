  <!doctype html>
  <html lang="en" data-bs-theme="">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hommie | Login</title>
    <!--favicon-->
    <link rel="icon" href="assets/images/hommielogo-preview.png" type="image/png">
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet">
    <script src="assets/js/pace.min.js"></script>

    <!--plugins-->
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/metisMenu.min.css">
    <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/mm-vertical.css">
    <!--bootstrap css-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <!--main css-->
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="sass/main.css" rel="stylesheet">
    <link href="sass/dark-theme.css" rel="stylesheet">
    <link href="sass/blue-theme.css" rel="stylesheet">
    <link href="sass/responsive.css" rel="stylesheet">

    </head>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <style>
      .auth-cover-left {
        background-image: url('assets/images/auth/cover-login.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        position: relative;
        /* Tambahkan padding untuk memberi ruang di sekitar overlay */
        padding: 20px;
      }
      .overlay {
          position: absolute;
          /* Atur posisi dan ukuran overlay (contoh: 80% width dan height) */
          width: 85%;
          height: 85%;
          /* Pusatkan overlay */
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          /* Styling overlay */
          background-color: rgba(255, 255, 255, 0.7);
          display: flex;
          align-items: center;
          justify-content: center;
          text-align: center;
          /* Tambahkan border radius untuk sudut melengkung */
          border-radius: 15px;
          /* Tambahkan shadow untuk efek depth */
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      }
      .text {
          font-size: 3rem;
          font-weight: bold;
          color: #003366;
          /* Tambahkan padding untuk teks */
          padding: 20px;
      }
      .btn-success {
        background-color: #28a745; /* Warna hijau */
        border: none; /* Menghilangkan border */
        transition: background-color 0.3s, transform 0.2s; /* Animasi transisi */
      }
      .btn-success:hover {
          background-color: #218838; /* Warna hijau lebih gelap saat hover */
          transform: scale(1.05); /* Efek zoom saat hover */
      }

      .btn-success:focus {
          outline: none; /* Menghilangkan outline saat fokus */
          box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5); /* Menambahkan shadow saat fokus */
      }      
    </style>

  <body>
    <!--authentication-->

    <div class="section-authentication-cover">
      <div class="">
        <div class="row g-0">

          <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex border-end bg-transparent">
            <div class="overlay">
              <h1 class="mb-0" style="color: #289A84;">
                <span style="color:#152C5B ;">HOM</span>MIE.
              </h1>
            </div>
          </div>

          <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center border-top border-4 border-primary border-gradient-1">
            <div class="card rounded-0 m-3 mb-0 border-0 shadow-none bg-none">
              <div class="card-body p-sm-5">
                <div class="d-flex align-items-center mb-3">
                  <img src="{{ asset('assets/images/hommielogo-preview.png') }}" class="logo-img me-2" alt="" style="width: 100px; height: auto;">
                  <h3 class="mb-0" style="color: #289A84;">
                    <span style="color:#152C5B ;">Hom</span>mie
                  </h3>
                </div>                
                <h4 class="fw-bold">Get Started Now</h4>
                <p class="mb-0">Enter your credentials to login your account</p>

                <div class="row g-3 my-4">
                  <div class="col-12 col-lg-12">
                    <button class="btn btn-light py-2 font-text1 fw-bold d-flex align-items-center justify-content-center w-100"><img src="assets/images/apps/05.png" width="20" class="me-2" alt="">Google</button>
                  </div>
                </div>
                <div class="separator section-padding">
                  <div class="line"></div>
                  <p class="mb-0 fw-bold">OR</p>
                  <div class="line"></div>
                </div>

                <div class="form-body mt-4">
                  <form class="row g-3" id="loginForm" action="{{ route('login1') }}" method="POST">
                      @csrf
              
                      <div class="col-12">
                          <label for="inputEmailAddress" class="form-label">Email</label>
                          <input type="email" class="form-control" id="inputEmailAddress" name="email" placeholder="jhon@example.com" required>
                      </div>
                      <div class="col-12">
                          <label for="inputChoosePassword" class="form-label">Password</label>
                          <div class="input-group">
                              <input type="password" class="form-control" id="inputChoosePassword" name="password" placeholder="Enter Password" required>
                              <button type="button" class="input-group-text bg-transparent" onclick="togglePassword()">
                                  <i class="bi bi-eye-slash-fill" id="toggleIcon"></i>
                              </button>
                          </div>
                      </div>
              
                      <div class="col-md-6">
                          <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="remember">
                              <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                          </div>
                      </div>
              
                      <div class="col-md-6 text-end">
                          {{-- <a href="{{ route('password.request') }}">Forgot Password?</a> --}}
                          <a href="">Forgot Password?</a>
                      </div>
              
                      <div class="col-12">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill shadow">Login</button>
                        </div>
                    </div>            
                      <div class="col-12">
                          <div class="text-start">
                              <p class="mb-0">Don't have an account yet? <a href="{{ route('register') }}">Sign up here</a></p>
                          </div>
                      </div>
                  </form>
              </div>

            </div>
            </div>
          </div>

        </div>
        <!--end row-->
      </div>
    </div>

    <!--authentication-->
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>

    <script>
      function togglePassword() {
        let passwordInput = document.getElementById("inputChoosePassword");
        let toggleIcon = document.getElementById("toggleIcon");
    
        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          toggleIcon.classList.remove("bi-eye-slash-fill");
          toggleIcon.classList.add("bi-eye-fill");
        } else {
          passwordInput.type = "password";
          toggleIcon.classList.remove("bi-eye-fill");
          toggleIcon.classList.add("bi-eye-slash-fill");
        }
      }
    </script>    

  </body>
  </html>

  {{-- <!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hommie | Login</title>
  
  <!-- Favicon -->
  <link rel="icon" href="{{ asset('assets/images/hommielogo-preview.png') }}" type="image/png">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  
  <!-- Bootstrap & Plugins -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
  
  <!-- Custom CSS -->
  <style>
    :root {
      --primary: #152C5B;
      --secondary: #289A84;
      --light: #F8F9FA;
      --dark: #343A40;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light);
    }

    .auth-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .auth-card {
      background: white;
      border-radius: 1rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      max-width: 450px;
      width: 100%;
    }

    .auth-header {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      padding: 2rem;
      text-align: center;
      color: white;
    }

    .form-control:focus {
      border-color: var(--secondary);
      box-shadow: 0 0 0 0.2rem rgba(40, 154, 132, 0.25);
    }

    .btn-success {
      background: linear-gradient(45deg, var(--secondary), #21c493);
      border: none;
      transition: all 0.3s ease;
    }

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 15px rgba(40, 154, 132, 0.3);
    }

    .password-toggle {
      cursor: pointer;
      color: var(--secondary);
    }

    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #ccc;
    }

    .divider span {
      padding: 0 10px;
      color: #666;
    }

    .social-btn {
      background: #f5f5f5;
      color: #333;
      border: none;
      transition: all 0.2s ease;
    }

    .social-btn:hover {
      background: #e0e0e0;
    }

    @media (max-width: 768px) {
      .auth-left {
        display: none !important;
      }
    }
  </style>
</head>

<body>
  <!-- Authentication Section -->
  <div class="container auth-container py-4">
    <div class="row g-0">
      <!-- Left Column (Image) -->
      <div class="col-md-6 auth-left d-none d-md-flex align-items-center justify-content-center">
        <div class="p-5 text-white text-center">
          <h1 class="display-4 fw-bold mb-3">
            <span style="color: #fff;">HOM</span><span style="color: #289A84;">MIE</span>
          </h1>
          <p class="lead">Tingkatkan kenyamanan hunianmu bersama Hommie</p>
        </div>
      </div>

      <!-- Right Column (Form) -->
      <div class="col-md-6 d-flex align-items-center">
        <div class="card auth-card">
          <div class="card-body p-5">
            <!-- Logo -->
            <div class="d-flex align-items-center mb-4">
              <img src="{{ asset('assets/images/hommielogo-preview.png') }}" 
                   alt="Hommie Logo" 
                   width="50" 
                   class="me-3">
              <h3 class="mb-0" style="color: var(--secondary);">
                <span style="color: var(--primary);">Hom</span>mie
              </h3>
            </div>

            <!-- Welcome Text -->
            <h4 class="fw-bold mb-1">Welcome Back!</h4>
            <p class="text-muted mb-4">Sign in to continue to Hommie</p>

            <!-- Social Login -->
            <div class="d-grid gap-2 mb-4">
              <button class="btn social-btn d-flex align-items-center justify-content-center">
                <img src="{{ asset('assets/images/apps/google.png') }}" width="20" class="me-2" alt=""> Login with Google
              </button>
            </div>

            <!-- Divider -->
            <div class="divider">
              <span>or</span>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login1') }}" method="POST" id="loginForm">
              @csrf
              
              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" 
                       class="form-control form-control-lg" 
                       id="email" 
                       name="email" 
                       placeholder="Enter email"
                       required>
              </div>

              <!-- Password -->
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <input type="password" 
                         class="form-control form-control-lg" 
                         id="password" 
                         name="password" 
                         placeholder="Enter password"
                         required>
                  <button class="btn btn-outline-secondary password-toggle" 
                          type="button" 
                          onclick="togglePassword()">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                  </button>
                </div>
              </div>

              <!-- Remember Me & Forgot Password -->
              <div class="d-flex justify-content-between mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember" name="remember">
                  <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <a href="#" class="text-decoration-none" style="color: var(--secondary);">Forgot Password?</a>
              </div>

              <!-- Submit Button -->
              <div class="d-grid mb-4">
                <button type="submit" class="btn btn-success btn-lg rounded-pill">
                  Sign In
                </button>
              </div>
            </form>

            <!-- Register Link -->
            <p class="text-center mb-0">
              Don't have an account? <a href="{{ route('register') }}" style="color: var(--secondary);">Sign Up</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/pace.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>

  <!-- Password Toggle Script -->
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("bi-eye-slash");
        toggleIcon.classList.add("bi-eye");
      } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("bi-eye");
        toggleIcon.classList.add("bi-eye-slash");
      }
    }
  </script>
</body>
</html> --}}