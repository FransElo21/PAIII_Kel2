@extends('layouts.index-welcome')
@section('content')

@php
    $currentStep = 4;
@endphp

<style>
    :root {
        --primary: #289A84;
        --primary-light: #3ebf9b;
        --gray-light: #f8f9fa;
        --gray-dark: #343a40;
    }
    body { background: var(--gray-light); }

    .stepper {
        display: flex; align-items: center; justify-content: center;
        gap: 2px; margin-bottom: 1.5rem;
    }
    .circle {
        width: 40px; height: 40px; border-radius: 50%;
        background-color: #dcdcdc; color: #000; text-align: center; line-height: 40px;
        font-weight: bold; border: 1px solid var(--primary);
    }
    .circle.active, .circle.done {
        background-color: var(--primary); color: #fff; border-color: var(--primary);
    }
    .line { flex: 1; height: 3px; background-color: #dcdcdc; border-radius: 10px; }
    .line.active-line { background-color: var(--primary); }

    .card-custom {
        border: none;
        border-radius: 1.3rem;
        box-shadow: 0 0 26px rgba(40,154,132,0.08);
        background: linear-gradient(135deg, #fff 70%, #f4fbf9 100%);
        overflow: visible;
    }
    .btn-gradient-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        border: none;
        color: white;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(.45,1.5,.60,1);
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        letter-spacing: 0.03em;
        box-shadow: 0 6px 18px rgba(40, 154, 132, 0.13);
    }
    .btn-gradient-primary:hover {
        filter: brightness(1.09);
        transform: translateY(-2px) scale(1.035);
        box-shadow: 0 8px 26px rgba(40, 154, 132, 0.21);
    }
    .btn-download {
        background: #fff;
        color: var(--primary);
        border: 1.5px dashed #b6ede1;
        font-weight: 600;
        border-radius: 30px;
        padding: 0.45rem 1.3rem;
        margin-top: 0.7rem;
        transition: background 0.16s, color 0.13s;
    }
    .btn-download:hover {
        background: #e6f0ee;
        color: #18886c;
    }
    .checkmark {
        width: 64px; height: 64px; box-shadow: 0 4px 22px rgba(40, 154, 132, 0.25);
        background: linear-gradient(135deg, var(--primary) 0%, #3ebf9b 100%);
        color: #fff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.3rem;
        animation: bounceIn 1.1s cubic-bezier(.57,2.3,.54,.77);
    }
    @keyframes bounceIn {
        0% { transform: scale(0); opacity: 0; }
        65% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(1); }
    }
    .booking-id {
        background: linear-gradient(90deg,#e7fcf4 40%, #e2f7fb 100%);
        font-size: 1rem;
        letter-spacing: 0.7px;
        padding: 0.58rem 1.2rem 0.58rem 1.2rem;
        border-radius: 999px;
        margin-bottom: 0.3rem;
        box-shadow: 0 2px 10px rgba(44,150,120,0.05);
        display: inline-block;
    }
    .booking-id span { font-weight: bold; }
    .booking-id:hover { background-color: #e6f0ee; }
    .text-gray-800 { color: #2d3748 !important; }
    .text-gray-600 { color: #718096 !important; }
    .text-primary { color: var(--primary) !important; }
</style>

<div class="container pt-4">
    <!-- Stepper -->
    <div class="stepper mb-3">
        <div class="circle @if($currentStep >= 1) done @endif @if($currentStep === 1) active @endif">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="line @if($currentStep >= 2) active-line @endif"></div>
        <div class="circle @if($currentStep >= 2) done @endif @if($currentStep === 2) active @endif">
            <i class="bi bi-envelope-check-fill"></i>
        </div>
        <div class="line @if($currentStep >= 3) active-line @endif"></div>
        <div class="circle @if($currentStep >= 3) done @endif @if($currentStep === 3) active @endif">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="line @if($currentStep >= 4) active-line @endif"></div>
        <div class="circle @if($currentStep >= 4) done @endif @if($currentStep === 4) active @endif">
            <i class="bi bi-check-circle-fill"></i>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-custom border-0 overflow-hidden">
                <div class="card-body p-5 text-center">
                    <div class="checkmark mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <h2 class="fw-bold mb-2 text-gray-800" style="letter-spacing: .5px;">Pembayaran Berhasil!</h2>
                    
                    <div class="booking-id mx-auto mt-3 mb-1 shadow-sm">
                        <small class="text-gray-600">Booking ID: </small>
                        <span class="text-primary">{{ $booking_id }}</span>
                    </div>
                    
                    <!-- Tombol Download Resi -->
                    <div>
                        <a href="{{ route('payment.downloadResi', ['booking_id' => $booking_id]) }}"
                           class="btn btn-download d-inline-flex align-items-center"
                           target="_blank">
                            <i class="bi bi-file-earmark-arrow-down me-2"></i>
                            Download Resi (PDF)
                        </a>
                    </div>

                    <p class="text-gray-600 mb-4 mt-4" style="font-size: 1.08rem;">
                        Terima kasih telah menggunakan layanan kami.<br>
                        Pembayaran Anda sudah kami terima dan pesanan sedang diproses.
                    </p>

                    <a href="{{ route('landingpage') }}"
                        class="btn btn-gradient-primary d-inline-flex align-items-center shadow-sm mt-2">
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
