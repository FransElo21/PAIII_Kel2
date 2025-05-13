<!doctype html>
<html lang="en" data-bs-theme="">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hommie | Register</title>
  <!--favicon-->
	<link rel="icon" href="{{ asset('assets/images/newLogohommie.png') }}" type="" width="145">
  <!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet">
	<script src="assets/js/pace.min.js"></script>

  <!--plugins-->
  <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/metisMenu.min.css">
  <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/mm-vertical.css">
  <!--bootstrap css-->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!--main css-->
  <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="sass/main.css" rel="stylesheet">
  <link href="sass/dark-theme.css" rel="stylesheet">
  <link href="sass/blue-theme.css" rel="stylesheet">
  <link href="sass/responsive.css" rel="stylesheet">

  </head>

  <style>
    .btn-custom {
      background: linear-gradient(135deg, #38a169, #289A84); /* gradasi hijau */
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      box-shadow: 0 4px 14px rgba(56, 161, 105, 0.4);
      transition: all 0.3s ease;
      cursor: pointer;
      border-radius: 20px;
    }

    .btn-custom:hover {
      background: linear-gradient(135deg, #2f855a, #289A84); /* lebih gelap saat hover */
      box-shadow: 0 6px 18px rgba(56, 161, 105, 0.5);
      transform: translateY(-2px);
    }
  </style>
<body>

  @if ($errors->has('registration'))
  <div class="alert alert-danger">
    {{ $errors->first('registration') }}
  </div>
@endif

  <!--authentication-->

  <div class="mx-3 mx-lg-0">

  <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden border-3 p-4">
    <div class="row g-4">
      <div class="col-lg-6 d-flex">
        <div class="card-body">
          <div class="d-flex justify-content-center">
            <img src="{{ asset('assets/images/newLogohommie.png') }}" width="145" alt="">
          </div>
          <h4 class="fw-bold">Buat Akun Anda Sekarang!</h4>
          <p class="mb-0">Akses mudah ke berbagai pilihan hunian terbaik.</p>

          <div class="form-body mt-4">
            <form class="row g-3" id="registerForm" action="{{ route('insertRegister') }}" method="POST">
              @csrf
              <div class="col-12">
                <label for="inputUsername" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="inputUsername" name="username" placeholder="Jhon" value="{{ old('username') }}" required>
                @error('username')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <label for="inputEmailAddress" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmailAddress" name="email" placeholder="example@user.com" value="{{ old('email') }}" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Password -->
              <div class="col-12">
                <label for="inputChoosePassword" class="form-label">Password</label>
                <div class="input-group" id="togglePassword">
                  <input type="password" class="form-control @error('password') is-invalid @enderror" id="inputChoosePassword" name="password" placeholder="Masukkan Password" required>
                  <a href="javascript:;" class="input-group-text bg-transparent"><i class="bi bi-eye-slash-fill"></i></a>
                </div>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Konfirmasi Password -->
              <div class="col-12">
                <label for="inputConfirmPassword" class="form-label">Konfirmasi Password</label>
                <div class="input-group" id="toggleConfirmPassword">
                  <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="inputConfirmPassword" name="password_confirmation" placeholder="Konfirmasi Password" required>
                  <a href="javascript:;" class="input-group-text bg-transparent"><i class="bi bi-eye-slash-fill"></i></a>
                </div>
                @error('password_confirmation')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
                     
              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" required>
                  <label class="form-check-label" for="flexSwitchCheckChecked">Saya telah membaca dan menyetujui Syarat & Ketentuan</label>
                </div>
              </div>
              <div class="col-12">
                <div class="d-grid">
                  <button type="submit" class="btn-custom">Register</button>
                </div>
              </div>
              <div class="col-12">
                <div class="text-start">
                  <p class="mb-0">Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-6 d-lg-flex d-none">
        <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #38a169, #289A84);">
          <img src="assets/images/auth/register1.png" class="img-fluid" alt="">
        </div>
      </div>

    </div><!--end row-->
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

  <script>
  function toggleVisibility(containerId) {
    const $container = $('#' + containerId);
    $container.find('a').on('click', function (event) {
      event.preventDefault();
      const $input = $container.find('input');
      const $icon = $container.find('i');
      if ($input.attr("type") === "text") {
        $input.attr('type', 'password');
        $icon.addClass("bi-eye-slash-fill").removeClass("bi-eye-fill");
      } else {
        $input.attr('type', 'text');
        $icon.removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
      }
    });
  }

  $(document).ready(function () {
    toggleVisibility('togglePassword');
    toggleVisibility('toggleConfirmPassword');
  });
</script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

  {{-- <script>
    document.getElementById('registerForm').addEventListener('submit', function (e) {
      const password = document.getElementById('inputChoosePassword').value;
      const confirmPassword = document.getElementById('inputConfirmPassword').value;

      if (password !== confirmPassword) {
        e.preventDefault(); // menghentikan submit
        Swal.fire({
          icon: 'error',
          title: 'Password Tidak Cocok',
          text: 'Pastikan password dan konfirmasi password sama.'
        });
      }
    });
  </script> --}}
</body>

</html>