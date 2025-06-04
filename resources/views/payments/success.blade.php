@extends('layouts.index-welcome')
@section('content')

@php
    // Di halaman sukses, step terakhir (4) harus aktif
    $currentStep = 4;
@endphp

<style>
    :root {
        --primary: #289A84;
        --primary-light: #3ebf9b;
        --gray-light: #f8f9fa;
        --gray-dark: #343a40;
        --shadow-sm: 0 0 0 4px rgba(40, 154, 132, 0.15);
    }
    body { background: var(--gray-light); }

    /* Stepper */
    .stepper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2px;
        margin-bottom: 1.5rem;
    }
    .circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #dcdcdc;
        color: #000;
        text-align: center;
        line-height: 40px;
        font-weight: bold;
        border: 1px solid var(--primary);
    }
    .circle.active,
    .circle.done {
        background-color: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
    .line {
        flex: 1;
        height: 3px;
        background-color: #dcdcdc;
        border-radius: 10px;
    }
    .line.active-line {
        background-color: var(--primary);
    }

    /* Card Sukses */
    .card-custom {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.06);
    }
    .btn-gradient-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
    }
    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(40, 154, 132, 0.3);
    }
    .checkmark {
        width: 64px;
        height: 64px;
        box-shadow: 0 4px 20px rgba(40, 154, 132, 0.3);
        background-color: var(--primary);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        animation: bounceIn 1s ease-out;
    }
    @keyframes bounceIn {
        0% { transform: scale(0); opacity: 0; }
        60% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(1); }
    }
    .booking-id {
        background-color: var(--gray-light);
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        padding: 0.5rem 1rem;
        border-radius: 999px;
    }
    .booking-id:hover {
        background-color: #e6f0ee;
    }
    .text-gray-800 { color: #2d3748 !important; }
    .text-gray-600 { color: #718096 !important; }
    .text-primary { color: var(--primary) !important; }
    .border-dashed { border-style: dashed !important; }
</style>

<div class="container pt-4">
                <!-- Stepper di Halaman Sukses -->
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
                    @if($currentStep >= 2) done @endif 
                    @if($currentStep === 2) active @endif">
                    <i class="bi bi-envelope-check-fill"></i>
                </div>
                <div class="line 
                    @if($currentStep >= 3) active-line @endif"></div>

                {{-- STEP 3: Pembayaran --}}
                <div class="circle 
                    @if($currentStep >= 3) done @endif 
                    @if($currentStep === 3) active @endif">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="line 
                    @if($currentStep >= 4) active-line @endif"></div>

                {{-- STEP 4: Sukses --}}
                <div class="circle 
                    @if($currentStep >= 4) done @endif 
                    @if($currentStep === 4) active @endif">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <!-- Kartu Konfirmasi Sukses -->
            <div class="card card-custom border-0 overflow-hidden">
                <div class="card-body p-5 text-center">
                    <!-- Animasi Checkmark -->
                    <div class="checkmark mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>

                    <h2 class="fw-bold mb-3 text-gray-800">Pembayaran Berhasil!</h2>
                    
                    <!-- Booking ID -->
                    <div class="booking-id mb-4">
                        <small class="text-gray-600">Booking ID:</small> 
                        <span class="fw-medium text-primary">{{ $booking_id }}</span>
                    </div>

                    <p class="text-gray-600 mb-4">
                        Terima kasih telah menggunakan layanan kami. Pembayaran Anda telah kami terima dan pesanan sedang diproses.
                    </p>

                    <a href="{{ route('landingpage') }}" class="btn btn-gradient-primary d-inline-flex align-items-center shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" class="me-2">
                            <path d="M19 12H5M12 19l-7-7 7-7"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection