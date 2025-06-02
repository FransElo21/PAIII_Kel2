@extends('layouts.index-welcome')
@section('content')

@php
    // STEP 2: Konfirmasi Pemesanan
    $currentStep = 2;
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --brand: #289A84;
        --brand-dark: #227f6a;
        --soft-bg: #f7fafd;
        --text-primary: #152c5b;
        --text-secondary: #555;
        --card-radius: 16px;
        --shadow-light: 0 4px 12px rgba(0,0,0,0.05);
    }
    body {
        background: var(--soft-bg);
        color: var(--text-secondary);
        font-family: 'Inter', sans-serif;
    }

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

    /* Konfirmasi Container */
    .confirm-container {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
    }
    .confirm-main {
        flex: 1 1 65%;
        min-width: 300px;
    }
    .confirm-sidebar {
        flex: 1 1 30%;
        min-width: 260px;
        position: sticky;
        top: 20px;
        height: fit-content;
    }

    /* Box modern */
    .card-modern {
        background: #fff;
        border: none;
        border-radius: var(--card-radius);
        box-shadow: var(--shadow-light);
        margin-bottom: 24px;
        padding: 24px;
        transition: box-shadow 0.3s, transform 0.3s;
    }
    .card-modern:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .card-modern h4 {
        font-size: 1.2rem;
        color: var(--text-primary);
        margin-bottom: 16px;
        font-weight: 600;
    }
    .card-modern p, 
    .card-modern dt, 
    .card-modern dd,
    .card-modern li {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.5;
    }
    .card-modern dt {
        font-weight: 500;
        margin-top: 12px;
    }
    .card-modern dd {
        margin-left: 0;
        margin-bottom: 12px;
    }

    /* Gambar Properti */
    .property-image {
        width: 100%;
        border-radius: var(--card-radius);
        object-fit: cover;
        height: 220px;
    }

    /* List kamar */
    .room-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #eef2f5;
    }
    .room-item:last-child {
        border-bottom: none;
    }
    .room-info {
        flex: 1;
    }
    .room-info h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-primary);
    }
    .room-info small {
        display: block;
        margin-top: 4px;
        color: #777;
    }
    .room-price {
        text-align: right;
        font-weight: 600;
        color: var(--brand-dark);
    }

    /* Ringkasan Harga */
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin: 12px 0;
        font-size: 1rem;
    }
    .summary-row.total {
        margin-top: 16px;
        font-weight: 600;
        font-size: 1.2rem;
        color: var(--brand-dark);
    }

    /* Tombol */
    .btn-outline-modern {
        background: transparent;
        border: 1px solid #ccc;
        color: var(--text-primary);
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: background 0.3s, border-color 0.3s;
    }
    .btn-outline-modern:hover {
        background: #f0f0f0;
        border-color: #bbb;
    }
    .btn-primary-modern {
        background: var(--brand);
        color: #fff;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: background 0.3s, transform 0.3s;
    }
    .btn-primary-modern:hover {
        background: var(--brand-dark);
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .confirm-main, .confirm-sidebar {
            flex: 1 1 100%;
        }
        .stepper {
            flex-wrap: wrap;
            gap: 8px;
        }
    }
    /* Breadcrumb */
    .breadcrumb {
        /* padding: 0.75rem 1.25rem; */
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); */
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

<div class="container py-4">
    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Property</a></li>
        <li class="breadcrumb-item"><a href="#">{{ $property->property_name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Konfirmasi Pemesanan</li>
    </ol>

    <!-- Stepper -->
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

    <div class="confirm-container">
        <!-- Konten Utama -->
        <div class="confirm-main">
            @if(session('error'))
                <div class="card-modern" style="border-left: 4px solid #f44336; padding-left: 20px;">
                    <p style="color: #b71c1c; font-size:0.95rem;">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error') }}
                    </p>
                </div>
            @endif

            <!-- 1. Info Properti -->
            <div class="card-modern">
                <h4><i class="bi bi-building me-2 text-primary"></i>Properti</h4>
                @if(count($images) > 0)
                    <img src="{{ asset('storage/' . $images[0]->images_path) }}" 
                         alt="Gambar Properti" class="property-image mb-3">
                @endif
                <dl>
                    <dt>Nama Properti</dt>
                    <dd>{{ $property->property_name }}</dd>

                    <dt>Lokasi</dt>
                    <dd>{{ $property->subdistrict }}</dd>
                </dl>
            </div>

            <!-- 2. Info Pemesan & Tamu -->
            <div class="card-modern">
                <h4><i class="bi bi-person-lines-fill me-2 text-primary"></i>Data Pemesan & Tamu</h4>
                <dl>
                    <dt>Nama Pemesan</dt>
                    <dd>{{ $userDetails['name'] }}</dd>

                    <dt>NIK</dt>
                    <dd>{{ $userDetails['nik'] }}</dd>

                    <dt>Email</dt>
                    <dd>{{ $userDetails['email'] }}</dd>

                    <dt>Atas Nama</dt>
                    <dd>
                        {{ $userDetails['guest_name'] }} 
                        <span style="color:#777; font-style:italic;">
                            ({{ $userDetails['guest_option'] === 'saya' ? 'Saya sendiri' : 'Untuk orang lain' }})
                        </span>
                    </dd>
                </dl>
            </div>

            <!-- 3. Info Kamar -->
            <div class="card-modern">
                <h4><i class="bi bi-door-open-fill me-2 text-primary"></i>Kamar yang Dipilih</h4>
                @foreach($selectedRooms as $room)
                    <div class="room-item">
                        <div class="room-info">
                            <h5>{{ $room['room_type'] ?? 'Kamar' }} × {{ $room['quantity'] }} unit</h5>
                            <small>Harga per unit: Rp{{ number_format($room['price_per_room'], 0, ',', '.') }}</small>
                        </div>
                        <div class="room-price">
                            Rp{{ number_format($room['subtotal'], 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 4. Tanggal Menginap -->
            <div class="card-modern">
                <h4><i class="bi bi-calendar-check me-2 text-primary"></i>Tanggal Menginap</h4>
                <dl>
                    <dt>Check-In</dt>
                    <dd>{{ \Carbon\Carbon::parse($checkIn)->format('l, d M Y') }} (After 14:00)</dd>

                    <dt>Check-Out</dt>
                    <dd>{{ \Carbon\Carbon::parse($checkOut)->format('l, d M Y') }} (Before 12:00)</dd>
                </dl>
            </div>
        </div>

        <!-- Sidebar Ringkas -->
        <div class="confirm-sidebar">
            <!-- Ringkasan Harga -->
            <div class="card-modern">
                <h4><i class="bi bi-cash-coin me-2 text-primary"></i>Ringkasan Harga</h4>
                <div class="summary-row">
                    <span>Total Kamar</span>
                    <span>{{ array_sum(array_column($selectedRooms, 'quantity')) }} unit</span>
                </div>
                <div class="summary-row">
                    <span>Durasi</span>
                    <span>{{ \Carbon\Carbon::parse($checkIn)
                        ->diffInDays(\Carbon\Carbon::parse($checkOut)) }} malam</span>
                </div>
                <div class="summary-row total">
                    <span>Total Bayar</span>
                    <span>Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Aksi Tombol -->
            <div class="card-modern" style="text-align:center;">
                <a href="javascript:history.back()" class="btn-outline-modern me-2">
                    <i class="bi bi-arrow-left me-1"></i>Ubah Data
                </a>
                <form action="{{ route('booking.store') }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn-primary-modern">
                        <i class="bi bi-check-lg me-1"></i>Konfirmasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
