<!doctype html>
<html lang="en" data-bs-theme="">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hommie | Login</title>

  <!--favicon-->
  <link rel="icon" href="assets/images/hommielogo-preview.png" type="image/png">

  <!--loader-->
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

  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <style>
    .auth-cover-left {
      background-image: url('assets/images/auth/cover-login.jpg');
      background-size: cover;
      background-position: center;
      height: 100vh;
      position: relative;
      padding: 20px;
    }
    .overlay {
      position: absolute;
      width: 85%;
      height: 85%;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: rgba(255, 255, 255, 0.7);
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .text {
      font-size: 3rem;
      font-weight: bold;
      color: #003366;
      padding: 20px;
    }
    .btn-success {
      background-color: #28a745;
      border: none;
      transition: background-color 0.3s, transform 0.2s;
    }
    .btn-success:hover {
      background-color: #218838;
      transform: scale(1.05);
    }
    .btn-success:focus {
      outline: none;
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.5);
    }
  </style>
</head>
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
                  <button class="btn btn-light py-2 font-text1 fw-bold d-flex align-items-center justify-content-center w-100">
                    <img src="assets/images/apps/05.png" width="20" class="me-2" alt=""> Google
                  </button>
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
    </div>
  </div>

  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Password Toggle Script -->
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

  @if(session('error'))
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ e(session('error')) }}'
      });
    </script>
  @endif
</body>
</html>