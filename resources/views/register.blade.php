<!doctype html>
<html lang="en" data-bs-theme="">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TobaStay | Register</title>
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

  {{-- ditambahkan --}}
  <!-- Tambahkan CSS intl-tel-input -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
  {{-- /ditambahkan --}}

  </head>

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

        <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
          <div class="card rounded-0 m-3 border-0 shadow-none bg-none">
            <div class="card-body p-sm-5">
              <div class="d-flex align-items-center mb-3">
                <img src="{{ asset('assets/images/hommielogo-preview.png') }}" class="logo-img me-2" alt="" style="width: 100px; height: auto;">
                <h3 class="mb-0" style="color: #152C5B;">
                  <span style="color: #289A84;">Hom</span>mie
                </h3>
              </div>
              
              <h4 class="fw-bold">Get Started Now</h4>
              <p class="mb-0">Enter your credentials to create your account</p>

              <div class="row g-3 my-4">
                <div class="col-12 col-lg-6">
                  <button class="btn btn-filter py-2 font-text1 fw-bold d-flex align-items-center justify-content-center w-100"><img src="assets/images/apps/05.png" width="20" class="me-2" alt="">Google</button>
                </div>
                <div class="col col-lg-6">
                  <button class="btn btn-filter py-2 font-text1 fw-bold d-flex align-items-center justify-content-center w-100"><img src="assets/images/apps/17.png" width="20" class="me-2" alt="">Facebook</button>
                </div>
              </div>

              <div class="separator section-padding">
                <div class="line"></div>
                <p class="mb-0 fw-bold">OR</p>
                <div class="line"></div>
              </div>

              <div class="form-body mt-4">

                <form class="row g-3" id="registerForm" action="{{ route('insertRegister') }}" method="POST">
                  @csrf
              
                  <!-- Username -->
                  <div class="col-12">
                      <label for="inputUsername" class="form-label">Username</label>
                      <input type="text" class="form-control" id="inputUsername" name="username" required>
                  </div>
              
                  <!-- Email -->
                  <div class="col-12">
                      <label for="inputEmailAddress" class="form-label">Email Address</label>
                      <input type="email" class="form-control" id="inputEmailAddress" name="email" required>
                  </div>
              
                  <!-- Password -->
                  <div class="col-12">
                      <label for="inputChoosePassword" class="form-label">Password</label>
                      <div class="input-group">
                          <input type="password" class="form-control" id="inputChoosePassword" name="password" required>
                          <a href="javascript:;" class="input-group-text bg-transparent" onclick="togglePassword()">
                              <i class="bi bi-eye-slash-fill"></i>
                          </a>
                      </div>
                  </div>
              
                  <!-- Confirm Password -->
                  <div class="col-12">
                      <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                      <input type="password" class="form-control" id="inputConfirmPassword" name="password_confirmation" required>
                  </div>

                  <div class="col-12">
                    <label for="inputUserType" class="form-label">Tipe User</label>
                    <select class="form-control" id="inputUserType" name="user_type_id" required>
                      <option value="" selected disabled>Pilih tipe user</option>
                      @foreach($userTypes as $userType)
                      @if($userType->userType_name !== 'Admin')
                        <option value="{{ $userType->id }}">{{ $userType->userType_name }}</option>
                      @endif
                    @endforeach
                    </select>
                  </div>
              
                  <!-- Terms & Conditions -->
                  <div class="col-12">
                      <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" required>
                          <label class="form-check-label" for="flexSwitchCheckChecked">I agree to the Terms & Conditions</label>
                      </div>
                  </div>
              
                  <!-- Submit Button -->
                  <div class="col-12">
                      <div class="d-grid">
                          <button type="submit" class="btn btn-success btn-lg rounded-pill shadow">Register</button>
                      </div>
                  </div>
              
                  <!-- Login Link -->
                  <div class="col-12 text-center mt-3">
                      <p class="mb-0">Already have an account?
                          <a href="{{ route('login') }}" class="text-success fw-bold">Sign in here</a>
                      </p>
                  </div>
              </form>
              
              <!-- JS untuk Toggle Password -->
              <script>
                  function togglePassword() {
                      const passwordField = document.getElementById("inputChoosePassword");
                      const icon = document.querySelector(".input-group-text i");
                      if (passwordField.type === "password") {
                          passwordField.type = "text";
                          icon.classList.remove("bi-eye-slash-fill");
                          icon.classList.add("bi-eye-fill");
                      } else {
                          passwordField.type = "password";
                          icon.classList.remove("bi-eye-fill");
                          icon.classList.add("bi-eye-slash-fill");
                      }
                  }
              </script>
              
              <!-- Validasi Password -->
              <script>
                  document.getElementById("registerForm").addEventListener("submit", function (e) {
                      const password = document.getElementById("inputChoosePassword").value;
                      const confirmPassword = document.getElementById("inputConfirmPassword").value;
                      if (password !== confirmPassword) {
                          e.preventDefault();
                          alert("Passwords do not match!");
                      }
                  });
              </script>              
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
    $(document).ready(function () {
      $("#show_hide_password a").on('click', function (event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password i').addClass("bi-eye-slash-fill");
          $('#show_hide_password i').removeClass("bi-eye-fill");
        } else if ($('#show_hide_password input').attr("type") == "password") {
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass("bi-eye-slash-fill");
          $('#show_hide_password i').addClass("bi-eye-fill");
        }
      });
    });
  </script>

  {{-- ditambahkan --}}
  <!-- Tambahkan JS intl-tel-input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>
    // Inisialisasi intl-tel-input untuk Phone Number
    var input = document.querySelector("#inputPhoneNumber");
    var iti = window.intlTelInput(input, {
        initialCountry: "id", // Default ke Indonesia
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });

    // Validasi Password
    document.getElementById("registerForm").addEventListener("submit", function(e) {
        var password = document.getElementById("inputChoosePassword").value;
        var confirmPassword = document.getElementById("inputConfirmPassword").value;
        if (password !== confirmPassword) {
            e.preventDefault();
            alert("Passwords do not match!");
        }
    });

    // Toggle Password Visibility
    function togglePassword() {
        var passwordField = document.getElementById("inputChoosePassword");
        var icon = document.querySelector(".input-group-text i");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("bi-eye-slash-fill");
            icon.classList.add("bi-eye-fill");
        } else {
            passwordField.type = "password";
            icon.classList.remove("bi-eye-fill");
            icon.classList.add("bi-eye-slash-fill");
        }
      }
</script>

<!-- Tambahkan Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
  {{-- /ditambahkan --}}

</body>

</html>