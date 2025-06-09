@extends('layouts.owner.index-owner')
@section('content')

@php
    function getInitials($name) {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $w) {
            if ($w) $initials .= strtoupper($w[0]);
            if (strlen($initials) == 2) break;
        }
        return $initials;
    }
    $initials = getInitials($data->name ?? '');
    $avatarUrl = $data->photo ? asset('storage/' . $data->photo) : null;
@endphp

<!-- intl-tel-input CSS & JS (CDN) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/intlTelInput.min.js"></script>

@if(session('success') || session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 1700,
                showConfirmButton: false
            });
            @endif
            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                timer: 2000,
                showConfirmButton: false
            });
            @endif
        });
    </script>
@endif

<style>
    :root {
        --green: #289A84;
        --green-light: #49d2aa;
        --gray-light: #f6f7fb;
        --gray-dark: #495057;
        --white: #ffffff;
        --radius-lg: 1.25rem;
        --shadow-glass: 0 6px 32px 0 rgba(40, 154, 132, 0.12);
        --shadow-sm: 0 2px 8px rgba(40, 154, 132, 0.06);
        --transition: .25s cubic-bezier(.4,2,.3,1);
    }
    body { background: var(--gray-light);}
    .profile-glass {
        background: rgba(255,255,255,0.78);
        backdrop-filter: blur(7px);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-glass);
        overflow: hidden;
        transition: box-shadow var(--transition);
    }
    .profile-glass:hover {
        box-shadow: 0 12px 36px 0 rgba(40,154,132,0.18);
        transform: translateY(-4px) scale(1.012);
    }
    .profile-header {
        background: linear-gradient(120deg, var(--green) 0%, var(--green-light) 100%);
        min-height: 135px;
        position: relative;
        overflow: hidden;
    }
    .profile-avatar-wrapper {
        position: absolute;
        top: 95px;
        left: 50%;
        transform: translateX(-50%);
        width: 110px;
        height: 110px;
        background: var(--white);
        border-radius: 50%;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        border: 4px solid #fff;
        z-index: 2;
    }
    .profile-avatar-wrapper img {
        width: 100%; height: 100%; border-radius: 50%; object-fit: cover;
    }
    .avatar-initials {
        font-weight: 700;
        background: linear-gradient(120deg, var(--green) 0%, var(--green-light) 100%);
        color: var(--white);
        border-radius: 50%;
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem;
    }
    .edit-profile-body {
        padding-top: 80px; background: transparent; min-height: 220px;
    }
    .edit-form-label {
        color: #8b98a8; font-size: .97rem; font-weight: 500;
    }
    .form-control, .form-select {
        border-radius: .75rem;
        min-height: 44px;
        box-shadow: none;
        border: 1.5px solid #e2e6ea;
        transition: border .15s;
        font-size: 1.04rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--green);
        box-shadow: 0 0 0 2px rgba(40,154,132,0.08);
    }
    .btn-save-modern {
        background: linear-gradient(90deg, var(--green), var(--green-light));
        color: #fff !important;
        border: none;
        font-weight: 600;
        font-size: 1.07rem;
        border-radius: 2em;
        box-shadow: 0 2px 10px rgba(40,154,132,.08);
        padding: .58rem 2.5rem;
        margin-top: 2.1rem;
        transition: background .2s, box-shadow .22s;
    }
    .btn-save-modern:hover {
        background: linear-gradient(90deg, #22c6a4, #289A84 85%);
        color: #fff;
        box-shadow: 0 8px 18px rgba(40,154,132,.13);
        transform: translateY(-1px) scale(1.012);
    }
    /* intl-tel-input custom style */
    .iti { width: 100%; }
    .iti input[type=tel], .iti .form-control {
        height: 44px;
        padding: 0.375rem 0.75rem 0.375rem 58px;
        border-radius: .75rem;
        border: 1.5px solid #e2e6ea;
        font-size: 1.04rem;
        background: #fff;
        box-shadow: none;
        width: 100%;
        transition: border-color .15s;
    }
    .iti input[type=tel]:focus {
        border-color: #289A84;
        outline: 0;
        box-shadow: 0 0 0 0.15rem rgba(40,154,132,.14);
    }
    .iti__flag-container {
        height: 44px;
        border-top-left-radius: .75rem;
        border-bottom-left-radius: .75rem;
        background: #fff;
        border-right: 1px solid #e2e6ea;
    }
    .iti--allow-dropdown input, .iti--separate-dial-code input {
        background: #fff !important;
    }
    .iti__country-list {
        border-radius: 12px !important;
    }
    @media (max-width: 768px) {
        .profile-avatar-wrapper { width: 80px; height: 80px; top: 74px;}
        .edit-profile-body { padding-top: 60px;}
    }
</style>

<div class="container py-4">
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0" style="list-style: none;">
                @foreach ($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('profileowner.update') }}" method="POST" enctype="multipart/form-data" class="profile-glass p-4">
                @csrf
                <div class="profile-header mb-0">
                    <div class="profile-avatar-wrapper avatar-initials">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="Avatar">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                </div>
                <div class="edit-profile-body px-2 px-md-5 pb-2">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="edit-form-label mb-1">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="edit-form-label mb-1">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $data->username) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="edit-form-label mb-1">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $data->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="edit-form-label mb-1">Nomor HP</label>
                            <input 
                                type="tel"
                                id="phone_pemilik_properti"
                                name="phone_pemilik_properti"
                                class="form-control"
                                value="{{ old('phone_pemilik_properti', $data->phone_pemilik_properti ?? '') }}"
                                placeholder="81234567890"
                                maxlength="15"
                                required
                            >
                            <small class="text-muted">Pilih kode negara & isi nomor HP (tanpa 0 di depan)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="edit-form-label mb-1">Jenis Kelamin</label>
                            <select name="gender_pemilik_properti" class="form-select">
                                <option value="" disabled {{ !$data->gender_pemilik_properti ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('gender_pemilik_properti', $data->gender_pemilik_properti) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender_pemilik_properti', $data->gender_pemilik_properti) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="edit-form-label mb-1">Alamat</label>
                            <input type="text" name="address_pemilik_properti" class="form-control" value="{{ old('address_pemilik_properti', $data->address_pemilik_properti) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="edit-form-label mb-1">Foto KTP</label>
                            <input type="file" name="ktp" class="form-control" accept="image/*" id="inputKtp">
                            {{-- Preview KTP baru jika user memilih, atau tampilkan lama --}}
                            <div id="previewKtp">
                                @if($data->ktp)
                                    <img src="{{ asset('storage/' . $data->ktp) }}" alt="Foto KTP" class="img-thumbnail mt-2" style="max-width:140px;">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="edit-form-label mb-1">Foto Profil</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="Foto Profil" class="img-thumbnail mt-2" style="max-width:100px;">
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-save-modern mt-3">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var input = document.querySelector("#phone_pemilik_properti");
    var iti;
    if(input) {
      iti = window.intlTelInput(input, {
        initialCountry: "id",
        nationalMode: false,
        formatOnDisplay: true,
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"
      });

      // Saat submit form, set value input ke international (ex: +628123456789)
      var form = input.closest('form');
      if(form) {
        form.addEventListener('submit', function() {
          var intlNumber = iti.getNumber();
          input.value = intlNumber;
        });
      }

      // Set value lama ke intl-tel-input jika ada
      @if(old('phone_pemilik_properti', $data->phone_pemilik_properti ?? false))
        iti.setNumber("{{ old('phone_pemilik_properti', $data->phone_pemilik_properti ?? '') }}");
      @endif
    }
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // ... intl-tel-input code ...

    // Preview langsung untuk KTP
    const inputKtp = document.getElementById('inputKtp');
    const previewKtp = document.getElementById('previewKtp');
    if (inputKtp) {
      inputKtp.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
          let reader = new FileReader();
          reader.onload = function(ev) {
            previewKtp.innerHTML = '<img src="'+ev.target.result+'" class="img-thumbnail mt-2" style="max-width:140px;">';
          }
          reader.readAsDataURL(e.target.files[0]);
        }
      });
    }
  });
</script>

@endsection
