@extends('layouts.index-welcome')

@section('content')
@if (!$booking)
    <div class="alert alert-danger mt-5 text-center">Data booking tidak ditemukan.</div>
@else

@php
    $bookingId = $booking->id ?? $booking->booking_id;
@endphp

<style>
    .payment-card {
        border-radius: 1rem;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.06);
    }
    .payment-method:hover {
        transform: scale(1.02);
    }
    .payment-method.selected {
        border: 2px solid #289A84;
        background-color: #f0fff9;
    }
</style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card payment-card shadow-sm">
                <div class="card-body text-center p-4">
                    <h4 class="fw-bold mb-4">Pembayaran</h4>

                    <div class="alert alert-info">
                        <small class="text-muted">Booking ID</small>
                        <h5 class="fw-bold mb-0">#{{ $bookingId }}</h5>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3 text-start">
                        <h6 class="fw-bold mb-3">Detail Pemesan</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Nama:</span>
                            <strong>{{ $booking->guest_name }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Email:</span>
                            <strong>{{ $booking->email }}</strong>
                        </div>
                    </div>

                    <div class="alert alert-success">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-white">Total Pembayaran</small>
                            <h5 class="fw-bold text-danger mb-0">
                                Rp{{ number_format((int)$booking->total_price, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>

                    <!-- Form untuk mengirimkan data pembayaran ke backend -->
                    <form action="{{ route('payment.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $bookingId }}">
                        <input type="hidden" name="guest_name" value="{{ $booking->guest_name }}">
                        <input type="hidden" name="email" value="{{ $booking->email }}">
                        <input type="hidden" name="total_price" value="{{ $booking->total_price }}">

                        <button type="submit" class="btn btn-success btn-lg mt-3">
                            <i class="bi bi-credit-card me-2"></i>Lanjutkan Ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@endsection