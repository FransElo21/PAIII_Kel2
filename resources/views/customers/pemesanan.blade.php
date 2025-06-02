@extends('layouts.index-welcome')
@section('content')

@php
    // Step ini = 1 (Isi Data Diri)
    $currentStep = 1;
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --brand: #289A84;
        --primary: #152C5B;
        --soft-bg: #f7fafd;
        --radius-xl: 28px;
        --radius-md: 16px;
        --shadow-1: 0 4px 32px rgba(40, 154, 132, 0.08);
        --shadow-2: 0 8px 38px rgba(30, 60, 90, 0.10);
    }
    body { background: var(--soft-bg); }

    /* Stepper */
    .stepper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 2rem;
    }
    .stepper .circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid var(--brand);
        color: var(--brand);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s, color 0.3s, transform 0.3s;
    }
    .stepper .circle.active,
    .stepper .circle.done {
        background: var(--brand);
        color: #fff;
        border-color: var(--brand);
        transform: scale(1.1);
    }
    .stepper .line {
        flex: 1;
        height: 4px;
        background: #e5ecea;
        border-radius: 4px;
        transition: background 0.3s;
    }
    .stepper .line.active-line {
        background: var(--brand);
    }

    /* Gaya Card & Form */
    .card {
        border: none;
        border-radius: var(--radius-xl) !important;
        box-shadow: var(--shadow-1);
        background: #fff;
        transition: box-shadow 0.18s, transform 0.18s;
    }
    .card:hover {
        box-shadow: var(--shadow-2);
        transform: translateY(-3px) scale(1.01);
    }
    .card-header {
        background: transparent !important;
        border-bottom: none !important;
        padding-bottom: 0;
    }
    .card-title {
        font-size: 1.17rem;
        color: var(--primary);
        font-weight: 700;
    }
    .btn-custom {
        background: linear-gradient(100deg, #36b37e 0%, #289A84 100%);
        color: #fff;
        border-radius: 32px;
        font-weight: 600;
        letter-spacing: .02em;
        transition: box-shadow .18s, background .22s, transform .18s;
        box-shadow: 0 4px 20px rgba(40, 154, 132, 0.10);
    }
    .btn-custom:hover {
        background: linear-gradient(100deg, #289A84 0%, #36b37e 100%);
        transform: translateY(-2px) scale(1.03);
    }
    input.form-control, select.form-control, textarea.form-control {
        border-radius: var(--radius-md) !important;
        border: 1.5px solid #e3e7ed;
        font-size: 1.01rem;
        background: #f8fafb;
        transition: border-color .17s, box-shadow .17s;
    }
    input.form-control:focus, select.form-control:focus, textarea.form-control:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 2px rgba(40,154,132,0.11);
        background: #fff;
    }
    .form-label {
        font-weight: 500;
        color: #295c4c;
        letter-spacing: .01em;
    }
    .alert-soft {
        background: #f4f9f6;
        color: #289A84;
        border: none;
    }
    .badge.bg-success.bg-opacity-10 {
        background: #e7fcf1 !important;
        color: #27ae60 !important;
    }
    ::-webkit-input-placeholder { color: #b0b7bc; }
    ::-moz-placeholder { color: #b0b7bc; }
    :-ms-input-placeholder { color: #b0b7bc; }
    ::placeholder { color: #b0b7bc; }
    .carousel-inner img {
        border-radius: var(--radius-md);
    }
    .progress-bar {
        transition: width 0.3s;
    }
    @media (max-width: 991px) {
        .col-lg-4.position-sticky {
            position: static !important;
            height: auto !important;
            overflow: visible !important;
            margin-top: 1.5rem;
        }
        .card {
            border-radius: 18px !important;
        }
    }
    .form-floating > .form-control, .form-floating > .form-select {
        background-color: #fff !important;
        color: #333;
    }
    .form-floating > label {
        color: #7d8591;
        opacity: .9;
        font-weight: 500;
    }
    /* Breadcrumb */
    .breadcrumb {
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #94a3b8;
    }
    .breadcrumb-item a {
        color: #64748b;
        transition: color 0.3s ease;
    }
    .breadcrumb-item a:hover {
        color: #289A84;
    }
    .breadcrumb-item.active {
        color: #289A84;
        font-weight: 600;
    }
</style>

<div class="container">
    <!-- Breadcrumb -->
    <ol class="breadcrumb pt-3 mb-4">
        <li class="breadcrumb-item"><a href="#">Property</a></li>
        <li class="breadcrumb-item"><a href="#">{{ $property->property_name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pemesanan</li>
    </ol>

    <!-- Stepper Modern (4 langkah: Isi Data Diri → Pembayaran → Konfirmasi Pemesanan → Sukses) -->
    <div class="stepper">
        {{-- STEP 1: Isi Data Diri --}}
        <div class="circle 
            @if($currentStep >= 1) done @endif 
            @if($currentStep === 1) active @endif">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="line 
            @if($currentStep >= 2) active-line @endif"></div>

        {{-- STEP 2: Konfirmasi Pemesanan --}}
        <div class="circle 
            @if($currentStep > 2) done @endif 
            @if($currentStep === 2) active @endif">
            <i class="bi bi-envelope-check-fill"></i>
        </div>
        <div class="line 
            @if($currentStep >= 3) active-line @endif"></div>

        {{-- STEP 3: Pembayaran --}}
        <div class="circle 
            @if($currentStep > 3) done @endif 
            @if($currentStep === 3) active @endif">
            <i class="bi bi-credit-card-2-back-fill"></i>
        </div>
        <div class="line 
            @if($currentStep >= 4) active-line @endif"></div>

        {{-- STEP 4: Sukses --}}
        <div class="circle 
            @if($currentStep === 4) active 
            @elseif($currentStep > 4) done 
            @endif">
            <i class="bi bi-check-circle-fill"></i>
        </div>
    </div>

    <div class="row">
        <!-- Main Form (Kolom Kiri) -->
        <div class="col-lg-8 col-md-12 order-1 order-lg-0">
            {{-- Tampilkan error/kesalahan --}}
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Card 1: Isi Data Diri -->
            <div class="card rounded-5 mb-4 shadow-sm">
                <div class="card-header bg-white py-3 px-4">
                    <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                        <i class="bi bi-person-fill me-2 text-primary"></i> Isi Data Diri
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('booking.confirm.post') }}" method="POST" autocomplete="off">
                        @csrf
                        {{-- Hidden Inputs dari session --}}
                        <input type="hidden" name="property_id" value="{{ session('booking.property_id') }}">
                        <input type="hidden" name="check_in" value="{{ session('booking.check_in') }}">
                        <input type="hidden" name="check_out" value="{{ session('booking.check_out') }}">
                        <input type="hidden" name="total_price" value="{{ session('booking.total_price') }}">
                        <input type="hidden" name="rooms" value='@json(session('booking.selected_rooms'))'>

                        <!-- Nama Lengkap -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', Auth::user()->name ?? '') }}" required>
                            <label for="name">Nama Lengkap</label>
                        </div>

                        <!-- NIK dengan feedback dan progress -->
                        <div class="form-floating mb-3">
                            <input type="text"
                                class="form-control @error('nik') is-invalid @enderror"
                                id="nik"
                                name="nik"
                                required
                                maxlength="16"
                                minlength="16"
                                value="{{ old('nik') }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)">
                            <label for="nik">NIK</label>
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                          <div class="progress-bar" id="nik-progress" style="width:0%;background:var(--brand)"></div>
                        </div>
                        <small id="nik-feedback" class="form-text text-muted">Masukkan tepat 16 angka.</small>

                        <!-- Email -->
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', Auth::user()->email ?? '') }}" required>
                            <label for="email">Email</label>
                        </div>

                        <!-- Siapa yang menginap -->
                        <div class="mb-3">
                            <label class="form-label"><strong>Siapa yang menginap?</strong></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="guest_option" id="sayaTamu"
                                    value="saya" {{ old('guest_option', 'saya') === 'saya' ? 'checked' : '' }}>
                                <label class="form-check-label" for="sayaTamu">Saya adalah tamu</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="guest_option" id="bookingOrangLain"
                                    value="lain" {{ old('guest_option') === 'lain' ? 'checked' : '' }}>
                                <label class="form-check-label" for="bookingOrangLain">
                                    Saya memesankan untuk orang lain
                                </label>
                            </div>
                        </div>

                        <!-- Nama Tamu (Jika "Lain") -->
                        <div id="guest_option" class="{{ old('guest_option') === 'lain' ? '' : 'd-none' }}">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="namaTamuLain" name="nama_tamu"
                                    value="{{ old('nama_tamu') }}">
                                <label for="namaTamuLain">Nama Lengkap Tamu</label>
                            </div>
                        </div>

                        <!-- Alert Informasi -->
                        <div class="alert alert-soft rounded-3">
                            Pada saat masuk property, mohon siapkan kartu identitas asli untuk verifikasi.
                        </div>
                            </div>
                        </div>

                        <!-- Card 2: Kamar yang Dipilih -->
                        <div class="card rounded-5 mb-4 shadow-sm">
                            <div class="card-header bg-white py-3 px-4">
                                <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                                    <i class="bi bi-door-open-fill me-2 text-primary"></i> Kamar yang Dipilih
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                @foreach ($selectedRooms as $room)
                                    <div class="card mb-2" style="background:#f7fafc;">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ $room['room_type'] ?? 'Kamar' }} | {{ $room['quantity'] }} kamar
                                                </h6>
                                                <small class="text-muted">
                                                    Harga: Rp{{ number_format($room['price_per_room'], 0, ',', '.') }}
                                                </small>
                                            </div>
                                            <span class="badge bg-success bg-opacity-10 fs-6" style="font-size:1.09rem;">
                                                Rp{{ number_format($room['subtotal'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="mt-3 text-end">
                                    <a href="javascript:history.back()" class="text-decoration-none text-success" title="Kembali ke halaman sebelumnya">
                                        Ubah Kamar
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Total Harga -->
                        <div class="card rounded-5 mb-4 shadow-sm">
                            <div class="card-header bg-white py-3 px-4">
                                <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                                    <i class="bi bi-cash me-2 text-primary"></i> Ringkasan
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="alert alert-soft mt-4 p-3 d-flex justify-content-between align-items-center rounded-3">
                                    <div>
                                        <strong>Total</strong><br>
                                        <span class="text-danger h4">
                                            IDR {{ number_format(array_sum(array_column($selectedRooms, 'subtotal')), 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-custom px-4">Lanjutkan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Tutup form tag di akhir --}}
                    </form>
                </div>

        <!-- Sidebar (Kolom Kanan) -->
        <div class="col-lg-4 col-md-12 position-sticky top-0 order-0 order-lg-1" style="height: 100vh; overflow-y: auto;">
            <div class="card mb-4 rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $property->property_name }}</h5>
                    <hr class="my-3">

                    <!-- Carousel Gambar -->
                    <div id="propertyCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($images as $key => $image)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->images_path) }}"
                                         class="d-block w-100 rounded-3 shadow-sm" alt="...">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button"
                                data-bs-target="#propertyCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button"
                                data-bs-target="#propertyCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>

                    <!-- Lokasi Property -->
                    <div class="row g-0 mb-3">
                        <div class="col-md-12">
                            <p class="card-text text-muted small">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $property->subdistrict }}
                            </p>
                        </div>
                    </div>

                    <!-- Tanggal Menginap -->
                    <div class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card bg-white border rounded-3 shadow-sm p-3 text-center">
                                    <h6 class="text-muted mb-1">Check-In</h6>
                                    <h5 class="fw-bold">
                                        {{ \Carbon\Carbon::parse(session('booking.check_in'))->format('D, j M Y') }}
                                    </h5>
                                    <small class="text-muted">After 14:00</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-white border rounded-3 shadow-sm p-3 text-center">
                                    <h6 class="text-muted mb-1">Check-Out</h6>
                                    <h5 class="fw-bold">
                                        {{ \Carbon\Carbon::parse(session('booking.check_out'))->format('D, j M Y') }}
                                    </h5>
                                    <small class="text-muted">Before 12:00</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Harga -->
                    <div class="alert alert-soft p-3 d-flex justify-content-between align-items-center rounded-3 bg-light-blue">
                        <div class="text-end">
                            <span class="text-danger h5 fw-bold">
                                Rp{{ number_format(session('booking.total_price'), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
    // Toggle untuk pilihan "Saya/Tamu Lain"
    document.getElementById('bookingOrangLain')?.addEventListener('change', function () {
        const guestInput = document.getElementById('guest_option');
        if (this.checked) {
            guestInput.classList.remove('d-none');
        }
    });
    document.getElementById('sayaTamu')?.addEventListener('change', function () {
        const guestInput = document.getElementById('guest_option');
        if (this.checked) {
            guestInput.classList.add('d-none');
        }
    });
    // Auto toggle saat reload (jika old('guest_option') === 'lain')
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('bookingOrangLain')?.checked) {
            document.getElementById('guest_option').classList.remove('d-none');
        }
    });

    // Feedback NIK Interaktif
    document.getElementById('nik')?.addEventListener('input', function () {
        const input = this;
        const feedback = document.getElementById('nik-feedback');
        const progress = document.getElementById('nik-progress');
        const percent = Math.min((input.value.length / 16) * 100, 100);
        progress.style.width = percent + "%";
        if (input.value.length === 16) {
            feedback.textContent = 'NIK valid.';
            feedback.classList.add('text-success');
        } else {
            feedback.textContent = 'Masukkan tepat 16 angka. Sisa: ' + (16 - input.value.length) + ' digit.';
            feedback.classList.remove('text-success');
        }
    });
</script>

@endsection
