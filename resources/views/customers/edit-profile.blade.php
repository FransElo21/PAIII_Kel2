@extends('layouts.index-welcome')
@section('content')

<!-- intl-tel-input CSS & JS (CDN) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/intlTelInput.min.js"></script>

<style>
    /* Intl-tel-input full width */
    .iti {
        width: 100%;
    }

    /* Samakan tinggi & style dengan Bootstrap form-control lain */
    .iti input[type=tel], .iti input.form-control, .iti .form-control {
        height: 48px;
        padding: 0.375rem 0.75rem 0.375rem 58px;
        /* padding-left buat flag */
        font-size: 1rem;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        background: #fff;
        box-shadow: none;
        width: 100%;
        transition: border-color 0.15s;
    }
    /* Saat focus, samakan border seperti bootstrap */
    .iti input[type=tel]:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.15rem rgba(13,110,253,.25);
    }
    /* Biar flag section tetap rapi & setara tinggi input */
    .iti__flag-container {
        height: 48px;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
        background: #fff;
        border-right: 1px solid #dee2e6;
    }
    /* Hapus background abu2 yang default di flag */
    .iti--allow-dropdown input, .iti--separate-dial-code input {
        background: #fff !important;
    }
    /* Optional: hapus shadow outline saat focus di flag */
    .iti__country-list {
        border-radius: 12px !important;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">Edit Profil Penyewa</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('profileuser.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name', $data->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="{{ old('email', $data->email) }}" required>
                        </div>
                        <!-- Form HTML -->
                        <div class="mb-3">
                        <label for="phone_number_penyewa" class="form-label">Nomor HP</label>
                        <input type="tel" name="phone_number_penyewa" id="phone_number_penyewa" class="form-control"
                                value="{{ old('phone_number_penyewa', $data->phone_number_penyewa) }}">
                        </div>
                        <div class="mb-3">
                            <label for="address_penyewa" class="form-label">Alamat</label>
                            <input type="text" name="address_penyewa" id="address_penyewa" class="form-control"
                                   value="{{ old('address_penyewa', $data->address_penyewa) }}">
                        </div>
                        <div class="mb-3">
                            <label for="gender_penyewa" class="form-label">Jenis Kelamin</label>
                            <select name="gender_penyewa" id="gender_penyewa" class="form-control">
                                <option value="">- Pilih -</option>
                                <option value="Laki-laki" {{ old('gender_penyewa', $data->gender_penyewa) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender_penyewa', $data->gender_penyewa) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="photo_profil" class="form-label">Foto Profil</label>
                            <input type="file" name="photo_profil" id="photo_profil" class="form-control">
                            @if ($data->photo_profil)
                                <img src="{{ asset('storage/' . $data->photo_profil) }}" alt="Foto Profil" class="mt-2 rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="mt-4 text-end">
                            <a href="{{ route('profileuser.show') }}" class="btn btn-secondary rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-success rounded-pill px-4 ms-2">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var input = document.querySelector("#phone_number_penyewa");
    var iti;
    if(input) {
      iti = window.intlTelInput(input, {
        initialCountry: "id",
        nationalMode: false,
        formatOnDisplay: true,
        separateDialCode: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"
      });

      // Saat submit form, set value ke format internasional
      var form = input.closest('form');
      if(form) {
        form.addEventListener('submit', function(e) {
          // Set value input ke international (ex: +628123456789)
          var intlNumber = iti.getNumber();
          input.value = intlNumber;
        });
      }
    }
  });
</script>

@endsection
