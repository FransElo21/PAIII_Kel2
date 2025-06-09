<!doctype html>
<html lang="en" data-bs-theme="">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hommie | Ubah Kata Sandi</title>
  <!--favicon-->
  <link rel="icon" href="{{ asset('assets/images/newLogohommie.png') }}" type="image/png">
  <!-- loader-->
  <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
  <script src="{{ asset('assets/js/pace.min.js') }}"></script>

  <!--plugins-->
  <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/plugins/metismenu/metisMenu.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/plugins/metismenu/mm-vertical.css') }}" rel="stylesheet">
  <!-- bootstrap css -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- bootstrap icons for eye toggle -->
  <link href="{{ asset('assets/plugins/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <!-- Material Icons (optional) -->
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!-- main css -->
  <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/main.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/dark-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/blue-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/responsive.css') }}" rel="stylesheet">

  <style>
    /* Gradient button */
    .btn-custom {
      background: linear-gradient(135deg, #289A84, #38a169);
      color: #fff;
      padding: .75rem 1.5rem;
      border-radius: .5rem;
      font-weight: 500;
      transition: transform .2s, box-shadow .2s;
      box-shadow: 0 4px 12px rgba(40,154,132,0.3);
    }
    .btn-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(40,154,132,0.4);
    }
    /* Input‐group for toggle */
    .input-group .form-control {
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
    }
    .input-group-text {
      background: #fff;
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <!--authentication-->
  <div class="mx-3 mx-lg-0">
    <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden p-4">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6 d-flex">
          <div class="card-body">

            <!-- Logo & Header -->
            <img src="{{ asset('assets/images/newLogohommie.png') }}" class="mb-4" width="145" alt="Logo">
            <h4 class="fw-bold mb-2">Ubah Kata Sandi Baru</h4>
            <p class="mb-4">Masukkan kata sandi baru Anda di bawah ini.</p>

            <!-- Flash & Errors -->
            @if(session('status'))
              <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <!-- Form Reset -->
            <div class="form-body mt-4">
              <form method="POST" action="{{ route('password.update') }}" class="row g-3">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- New Password with toggle -->
                <div class="col-12">
                  <label for="NewPassword" class="form-label">Password Baru</label>
                  <div class="input-group" id="show_hide_password_new">
                    <input id="NewPassword" name="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Masukkan password baru" required>
                    <span class="input-group-text"><i class="bi bi-eye-slash-fill"></i></span>
                  </div>
                  @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Confirm Password with toggle -->
                <div class="col-12">
                  <label for="ConfirmPassword" class="form-label">Konfirmasi Password</label>
                  <div class="input-group" id="show_hide_password_confirm">
                    <input id="ConfirmPassword" name="password_confirmation" type="password"
                           class="form-control" placeholder="Ulangi password baru" required>
                    <span class="input-group-text"><i class="bi bi-eye-slash-fill"></i></span>
                  </div>
                </div>

                <!-- Buttons -->
                <div class="col-12">
                  <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-custom" style="border-radius: 30px ;">Ubah Password</button>
                    <a href="{{ route('login') }}" class="btn btn-light">Kembali ke Login</a>
                  </div>
                </div>
              </form>
            </div>

          </div>
        </div>

        <!-- Illustration -->
        <div class="col-lg-6 d-lg-flex d-none">
          <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center"
               style="background: linear-gradient(135deg, #289A84, #38a169);">
            <img src="{{ asset('assets/images/auth/reset-password1.png') }}"
                 class="img-fluid" alt="Ilustrasi Reset">
          </div>
        </div>
      </div><!--end row-->
    </div>
  </div>
  <!--authentication-->

  <!--scripts-->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script>
    ['new','confirm'].forEach(function(suffix) {
      var wrapper = document.getElementById('show_hide_password_' + suffix);
      var input   = wrapper.querySelector('input');
      var icon    = wrapper.querySelector('i');
      wrapper.querySelector('.input-group-text').addEventListener('click', function(e) {
        e.preventDefault();
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.replace('bi-eye-slash-fill','bi-eye-fill');
        } else {
          input.type = 'password';
          icon.classList.replace('bi-eye-fill','bi-eye-slash-fill');
        }
      });
    });
  </script>
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
