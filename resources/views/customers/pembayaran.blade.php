<!-- resources/views/customers/payment.blade.php -->
@extends('layouts.index-welcome')
@section('content')

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

    .payment-method.selected {
        border: 2px solid #289A84;
        background-color: #f0fff9;
        position: relative;
    }
    .payment-method.selected::after {
        content: "✓";
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 1.5rem;
        color: #289A84;
    }
    .step {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .step .circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        box-shadow: 0 0 0 4px #e7f1ff;
    }

    .step .line {
        flex-grow: 1;
        height: 3px;
        background-color: #dee2e6;
        margin: 0 10px;
        margin-top: 16px;
        border-radius: 10px;
    }

    .border-dashed {
        border-style: dashed !important;
    }

    .alert-soft {
        background-color: #f8f9fa;
        border-color: #d3dce6;
        color: #495057;
    }

    .card-custom {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.06);
    }

    .btn-custom {
        border-radius: 50px;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
    }
    .stepper {
        display: flex;
        align-items: center;
        gap: 8px;
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
        border: 1px solid #289A84;
    }

    .circle.active {
        background-color: #289A84;
        color: #fff;
    }

    .line {
        flex: 1;
        width: 300px;
        height: 3px;
        background-color: #dcdcdc;
    }

    .line.active-line {
        background-color: #289A84;
    }
    
    .room-card {
        transition: all 0.3s ease;
    }
    
    .room-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .quantity-badge {
        top: 8px;
        right: 8px;
        z-index: 10;
    }
    .breadcrumb {
              display: flex;
              flex-wrap: wrap;
              list-style: none;
            }
        
            .breadcrumb-item + .breadcrumb-item::before {
              content: "›";
              padding: 0 0.5rem;
              color: #6c757d;
            }
        
            .breadcrumb-item a {
              text-decoration: none;
              color: #6c757d;
            }
        
            .breadcrumb-item.active {
              color: #007bff;
              pointer-events: none;
            }
</style>

<div class="container">

    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Property</a></li>
        <li class="breadcrumb-item"><a href="#">nama-property</a></li>
        <li class="breadcrumb-item">Pemesanan</li>
        <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
    </ol>

    <!-- Stepper -->
    <div class="stepper d-flex align-items-center justify-content-center mb-4">
        <div class="circle active">1</div>
        <div class="line active-line"></div>
        <div class="circle active">2</div>
        <div class="line "></div>
        <div class="circle">3</div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card payment-card shadow-sm">
                <div class="card-header bg-white py-4">
                    <h4 class="fw-bold mb-0">Pembayaran</h4>
                </div>
                <div class="card-body p-4">

                    <!-- Informasi Booking -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Booking ID</small>
                                {{-- <h5 class="fw-bold mb-0">#{{ $booking->id }}</h5> --}}
                            </div>
                            <div class="text-end">
                                <small class="text-muted">Durasi</small>
                                <h5 class="fw-bold mb-0">
                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('j M') }} 
                                    - 
                                    {{ \Carbon\Carbon::parse($booking->check_out)->format('j M Y') }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Kamar -->
                    <h6 class="fw-bold mb-3">Kamar yang Dipilih</h6>
                    {{-- @foreach ($booking->rooms as $room)
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $room->room_type }}</h6>
                                    <small class="text-muted">
                                        {{ $room->quantity }} kamar x Rp{{ number_format($room->price_per_room, 0, ',', '.') }}
                                    </small>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success fs-6">
                                    Rp{{ number_format($room->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach --}}

                    <!-- Detail Pengguna -->
                    <div class="bg-light p-3 rounded-3">
                        <h6 class="fw-bold mb-3">Detail Pemesan</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person-circle text-primary me-2"></i>
                            <span>{{ $booking->guest_name }}</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span>{{ $booking->email }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-credit-card-2-front text-primary me-2"></i>
                            <span>{{ $booking->nik }}</span>
                        </div>
                    </div>

                    <!-- Total Harga -->
                    <div class="alert alert-light-blue mt-4 p-3 d-flex justify-content-between align-items-center rounded-3">
                        <div>
                            <small class="text-muted">Total Pembayaran</small>
                            <h5 class="fw-bold mb-0 text-danger">
                                Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <h6 class="fw-bold my-3">Pilih Metode Pembayaran</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card payment-method" data-method="bank" onclick="selectPaymentMethod(this)">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-university text-primary me-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h6 class="mb-0">Transfer Bank</h6>
                                            <small class="text-muted">BCA, BNI, BRI, Mandiri</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card payment-method" data-method="ewallet" onclick="selectPaymentMethod(this)">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-mobile-alt text-primary me-3" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <h6 class="mb-0">E-Wallet</h6>
                                            <small class="text-muted">OVO, GoPay, Dana</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pembayaran -->
                    <div id="payment-details" class="d-none mb-4">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <span id="payment-instructions"></span>
                        </div>
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <strong>No. Rekening / QR Code</strong>
                                    <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard()">
                                        <i class="bi bi-clipboard"></i> Salin
                                    </button>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="payment-number" value="" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="downloadQRCode()">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Bayar -->
                    <div class="d-grid gap-3">
                        <button type="button" id="pay-button" class="btn btn-success btn-lg" onclick="confirmPayment()" disabled>
                            <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </button>
                    </div>

                    <div id="payment-timer" class="alert alert-warning d-none text-center">
                        Waktu pembayaran tersisa: <span id="timer">30:00</span>
                    </div>
                    
                    <script>
                        // Timer otomatis
                        let timer = 1800; // 30 menit
                        const countdown = setInterval(() => {
                            if (timer <= 0) {
                                clearInterval(countdown);
                                document.getElementById('payment-timer').classList.replace('d-none', 'd-block');
                                document.getElementById('pay-button').disabled = true;
                            } else {
                                const minutes = Math.floor(timer / 60);
                                const seconds = timer % 60;
                                document.getElementById('timer').textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                                timer--;
                            }
                        }, 1000);
                    </script>

                    <img id="qr-code" src="" alt="QR Code" class="img-fluid d-none" />

                    <script>
                        function generateQRCode(data) {
                            const qrCode = new QRCode(document.getElementById("qr-code"), {
                                text: data,
                                width: 200,
                                height: 200,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        }

                        function downloadQRCode() {
                            const canvas = document.querySelector("#qr-code canvas");
                            const link = document.createElement("a");
                            link.download = "qrcode.png";
                            link.href = canvas.toDataURL();
                            link.click();
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function selectPaymentMethod(element) {
        document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        const method = element.getAttribute('data-method');
        const details = document.getElementById('payment-details');
        const payButton = document.getElementById('pay-button');
        const instructions = document.getElementById('payment-instructions');
        const paymentNumber = document.getElementById('payment-number');

        details.classList.remove('d-none');
        payButton.disabled = false;

        if (method === 'bank') {
            instructions.textContent = 'Silakan transfer ke rekening berikut:';
            paymentNumber.value = 'BCA 1234567890 a.n. Property Management';
        } else if (method === 'ewallet') {
            instructions.textContent = 'Scan QR Code atau salin kode pembayaran:';
            paymentNumber.value = 'DANA1234567890';
        }
    }

    function copyToClipboard() {
        const paymentNumber = document.getElementById('payment-number');
        paymentNumber.select();
        document.execCommand('copy');
        alert('Nomor pembayaran disalin: ' + paymentNumber.value);
    }

    function confirmPayment() {
        if (!document.querySelector('.payment-method.selected')) {
            alert('Pilih metode pembayaran terlebih dahulu');
            return;
        }

        // Simulasi pembayaran sukses
        if (confirm('Lanjutkan pembayaran?')) {

        }
    }
</script>

<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin melanjutkan pembayaran?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                {{-- <button type="button" class="btn btn-success" onclick="window.location.href='/pembayaran/{{ $booking->id }}/selesai'">Ya, Bayar</button> --}}
                <button type="button" class="btn btn-success">Ya, Bayar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmPayment() {
        if (!document.querySelector('.payment-method.selected')) {
            alert('Pilih metode pembayaran terlebih dahulu');
            return;
        }
        new bootstrap.Modal(document.getElementById('paymentModal')).show();
    }
</script>

@endsection