@extends('layouts.index-welcome')

@section('content')
@if (!$booking)
    <div class="alert alert-danger">Data booking tidak ditemukan.</div>
@endif

<style>
    .payment-card {
        border-radius: 1rem;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.06);
    }
    .payment-method {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-method:hover {
        transform: scale(1.02);
    }
    .payment-method.selected {
        border: 2px solid #289A84;
        background-color: #f0fff9;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card payment-card shadow-sm">
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold mb-4">Pembayaran</h4>

                    <!-- Informasi Booking -->
                    <div class="alert alert-info mb-4">
                        <small class="text-muted">Booking ID</small>
                        <h5 class="fw-bold">#{{ $booking->booking_id }}</h5>
                    </div>

                    <!-- Detail Pengguna -->
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <h6 class="fw-bold mb-3">Detail Pemesan</h6>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span>Nama:</span>
                            <strong>{{ $booking->guest_name }}</strong>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span>Email:</span>
                            <strong>{{ $booking->email }}</strong>
                        </div>
                    </div>

                    <!-- Total Harga -->
                    <div class="alert alert-success mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-white">Total Pembayaran</small>
                            <h5 class="fw-bold text-danger">
                                Rp{{ number_format((int)$booking->total_price, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>

                    <!-- Tombol Bayar Sekarang -->
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-success btn-lg" onclick="confirmPayment()">
                            <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmPayment() {
        fetch("{{ route('payment.process') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                booking_id: "{{ $booking->booking_id }}",
                guest_name: "{{ $booking->guest_name }}",
                email: "{{ $booking->email }}",
                total_price: "{{ $booking->total_price * 1 }}" // Pastikan integer
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function(result){
                        alert("Pembayaran berhasil!");
                        window.location.href = "{{ route('payment.success', ['bookingId' => $booking->booking_id]) }}";
                    },
                    onPending: function(result){
                        alert("Menunggu pembayaran...");
                    },
                    onError: function(result){
                        alert("Terjadi kesalahan saat pembayaran.");
                    }
                });
            } else {
                alert("Gagal mendapatkan token pembayaran.");
                console.error(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Gagal memulai pembayaran.");
        });
    }
</script>

<!-- Script Midtrans -->
<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js "
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>

@endsection