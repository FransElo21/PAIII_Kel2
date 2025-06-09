<!doctype html>
<html lang="id" data-bs-theme="">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hommie | Lupa Kata Sandi</title>

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('assets/images/newLogohommie.png') }}" type="image/png">

  <!-- Loader -->
  <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">

  <!-- Plugins CSS -->
  <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/plugins/metismenu/metisMenu.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/plugins/metismenu/mm-vertical.css') }}" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Google Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">

  <!-- Main CSS -->
  <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/main.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/dark-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/blue-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('sass/responsive.css') }}" rel="stylesheet">

  <style>
    /* Biar centang secara vertikal dan horizontal */
    body {
      background-color: #F5F8FA;
      font-family: 'Noto Sans', sans-serif;
    }
    .vh-100 {
      min-height: 100vh;
    }
    /* Card lebih lebar */
    .reset-card {
      max-width: 900px;
      width: 100%;
      border: none;
      border-radius: 1rem;
      box-shadow: 0 12px 36px rgba(0,0,0,0.12);
    }
    /* Padding lebih besar */
    .reset-card .card-body {
      padding: 3rem;
    }
    /* Header teks lebih besar */
    .reset-card h4 {
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
    }
    .reset-card p {
      font-size: 1.125rem;
      margin-bottom: 1.5rem;
    }
    /* Input lebih tinggi */
    .reset-card .form-control {
      height: 3.2rem;
      font-size: 1rem;
      border-radius: 0.75rem;
    }
    /* Tombol lebih besar */
    .btn-custom {
      background: linear-gradient(135deg, #289A84, #38a169);
      color: #fff;
      padding: 0.5rem 0.5rem;
      font-size: 1.125rem;
      border-radius: 2.75rem;
      box-shadow: 0 6px 20px rgba(40,154,132,0.3);
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .btn-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 24px rgba(40,154,132,0.4);
    }
    /* Ilustrasi lebih besar */
    .reset-illustration img {
      max-height: 400px;
      width: auto;
    }
  </style>
</head>

<body>
@if(session('status'))
  <div class="alert alert-success text-center">
    {{ session('status') }}
  </div>
@endif

  <div class="d-flex justify-content-center align-items-center vh-100 px-3">
    <div class="card reset-card overflow-hidden">
      <div class="row g-0">
        <!-- Form Side -->
        <div class="col-lg-6">
          <div class="card-body">
            <div class="text-center mb-4">
              <img src="{{ asset('assets/images/newLogohommie.png') }}" width="140" alt="Logo Hommie">
            </div>
            <h5 class="text-center">Lupa Kata Sandi?</h5>
            <p class="text-center">Masukkan email terdaftar untuk menerima link reset kata sandi Anda</p>

            <form method="POST" action="{{ route('password.forgot') }}">
              @csrf
              <div class="mb-4">
                <label for="email" class="form-label fs-5">Email</label>
                <input id="email" name="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="nama@contoh.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                  <div class="invalid-feedback fs-6">{{ $message }}</div>
                @enderror
              </div>

              <div class="d-grid mb-4">
                <button type="submit" class="btn btn-custom">Kirim Link Reset</button>
              </div>

              <div class="text-center">
                <a href="{{ route('login') }}" class="text-decoration-none fw-medium fs-6 text-secondary">
                  ← Kembali ke Halaman Masuk
                </a>
              </div>
            </form>
          </div>
        </div>

        <!-- Illustration Side -->
        <div class="col-lg-6 reset-illustration d-flex align-items-center justify-content-center" 
             style="background: linear-gradient(135deg, #289A84, #38a169);">
          <img src="{{ asset('assets/images/auth/forgot-password1.png') }}" alt="Ilustrasi Reset">
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/pace.min.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
