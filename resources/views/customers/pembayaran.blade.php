@extends('layouts.index-welcome')

@section('content')
@php
    // STEP 3: Pembayaran (3-langkah: Isi Data Diri → Konfirmasi → Pembayaran)
    $currentStep = 3;

    // Pastikan variabel $booking, $rooms, dan $snapToken sudah dikirim dari controller.
    $bookingId = $booking->booking_id;
    $statusClass = str_replace(' ', '-', $booking->status);
@endphp

<!-- Sertakan SweetAlert2 & Bootstrap Icons via CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

<style>
    body {
        background: #f6fafc;
    }
    /* Stepper (3 Langkah: Isi Data Diri → Konfirmasi → Pembayaran) */
    .stepper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 1.8rem;
    }
    .circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #fff;
        border: 2.5px solid #289A84;
        color: #289A84;
        font-weight: bold;
        font-size: 1.17rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(40,154,132,0.05);
        transition: background .2s, color .2s, border-color .2s;
    }
    .circle.active,
    .circle.done {
        background: #289A84;
        color: #fff;
        border-color: #289A84;
    }
    .line {
        flex: 1;
        height: 5px;
        background: #d0ebe0;
        border-radius: 12px;
        transition: background .19s;
    }
    .line.active-line {
        background: #289A84;
    }

    .payment-card {
        border-radius: 1.4rem;
        box-shadow: 0 8px 40px rgba(40, 154, 132, 0.07);
        border: none;
        background: #fff;
        transition: box-shadow .18s, transform .16s;
    }
    .payment-card:hover {
        box-shadow: 0 12px 54px rgba(40,154,132,0.13);
        transform: translateY(-2px) scale(1.01);
    }

    .status-badge {
        padding: 0.47em 1.2em;
        border-radius: 16px;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: .03em;
    }
    .status-badge.Belum-Dibayar {
        background: #fff9e7;
        color:    #d99400;
        border:   1px solid #ffd684;
    }
    .status-badge.Berhasil {
        background: #eaf7f2;
        color:      #27ae60;
        border:     1px solid #8bd9b3;
    }
    .status-badge.Selesai {
        background: #e3f2fd;
        color:      #1976d2;
        border:     1px solid #90caf9;
    }
    .status-badge.Dibatalkan {
        background: #fdecea;
        color:      #d32f2f;
        border:     1px solid #f99a9a;
    }
    .status-badge.Kadaluarsa {
        background: #f5f5f5;
        color:      #616161;
        border:     1px solid #e0e0e0;
    }

    .section-card {
        border-radius: 1rem;
        box-shadow: 0 2px 16px rgba(44,62,80,0.07);
        margin-bottom: 1.2rem;
        border: none;
        background: #f7fafc;
    }
    .section-card .card-header {
        background: #f8fbfa !important;
        border-bottom: none;
        padding-bottom: 0;
    }
    .alert-soft {
        background: #f4f9f6;
        color: #289A84;
        border: none;
    }
    .btn-custom-pay {
        background: linear-gradient(92deg, #36b37e 0%, #289A84 100%);
        color: #fff;
        border-radius: 36px;
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: .01em;
        transition: box-shadow .18s, background .22s, transform .18s;
        box-shadow: 0 4px 22px rgba(40, 154, 132, 0.13);
        padding: 10px 2.5rem;
    }
    .btn-custom-pay:hover {
        background: linear-gradient(92deg, #289A84 0%, #36b37e 100%);
        transform: translateY(-2px) scale(1.04);
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
        <li class="breadcrumb-item"><a href="#">{{ $booking->property_name }}</a></li>
        <li class="breadcrumb-item">Pemesanan</li>
        <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
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
        {{-- STEP 3: Pembayaran --}}
        <div class="circle 
            @if($currentStep === 3) active 
            @elseif($currentStep > 3) done 
            @endif">
            <i class="bi bi-cash-coin"></i>
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

    {{-- Tampilkan SweetAlert jika ada pesan session --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: "{{ session('success') }}",
                confirmButtonColor: '#289A84'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d32f2f'
            });
        </script>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card payment-card">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4 text-center" style="color:#289A84;">
                        <i class="bi bi-cash-coin me-1"></i> Pembayaran
                    </h4>

                    <!-- Booking Summary -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <span class="text-muted small">Booking ID</span>
                            <h5 class="fw-bold mb-0">#{{ $booking->booking_id }}</h5>
                        </div>
                        <span class="status-badge {{ $statusClass }}">
                            {{ $booking->status }}
                        </span>
                    </div>

                    <!-- Property Information -->
                    <div class="card section-card mb-4">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0">Properti</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="fw-bold">{{ $booking->property_name }}</h6>
                            <div class="row mt-3">
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Check-in</small>
                                    <p class="fw-bold mb-0">
                                        {{ \Carbon\Carbon::parse($booking->check_in)->format('D, d M Y') }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Check-out</small>
                                    <p class="fw-bold mb-0">
                                        {{ \Carbon\Carbon::parse($booking->check_out)->format('D, d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Information -->
                    <div class="card section-card mb-4">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0">Pemesan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Nama Lengkap</small>
                                    <div class="fw-bold">{{ $booking->guest_name }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">NIK</small>
                                    <div class="fw-bold">{{ $booking->nik }}</div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Email</small>
                                    <div class="fw-bold">{{ $booking->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Details -->
                    @if(!empty($rooms))
                    <div class="card section-card mb-4">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0">Kamar yang Dipesan</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($rooms as $room)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <div class="fw-bold">{{ $room->room_type }}</div>
                                        <small>Jumlah: {{ $room->quantity }} kamar</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-semibold text-success">
                                            Rp{{ number_format($room->subtotal,0,',','.') }}
                                        </div>
                                        <small class="text-muted">
                                            Harga: Rp{{ number_format($room->price_per_room,0,',','.') }}/malam
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Payment Summary -->
                    <div class="alert alert-soft d-flex justify-content-between align-items-center p-3 rounded-3 mb-4">
                        <div><strong>Total Bayar</strong></div>
                        <span class="h4 fw-bold text-danger mb-0">
                            Rp{{ number_format($booking->total_price,0,',','.') }}
                        </span>
                    </div>

                    <!-- Payment Button: hanya muncul jika status 'Belum Dibayar' -->
                    @if($booking->status === 'Belum Dibayar')
                        <div class="text-center mt-4">
                            <!-- Ketika diklik, panggil Midtrans Snap dengan token yang dikirim oleh controller -->
                            <button id="pay-button"
                                    type="button"
                                    class="btn btn-custom-pay btn-lg w-100">
                                <i class="bi bi-credit-card me-2"></i> Lanjutkan Pembayaran
                            </button>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button id="cancel-button"
                                    type="button"
                                    class="btn btn-outline-danger btn-lg w-100">
                                <i class="bi bi-x-circle me-2"></i> Batalkan Pesanan
                            </button>
                        </div>
                    @else
                        <div class="alert alert-success text-center mt-4 rounded-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Pembayaran sudah diproses. Terima kasih!
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Dapatkan snapToken yang dikirim dari controller lewat query param
    const snapToken = '{{ request()->query("snap_token", "") }}';

    // Tombol “Lanjutkan Pembayaran” langsung memanggil Midtrans snap.pay(...)
    document.getElementById('pay-button')?.addEventListener('click', function() {
        if (! snapToken) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Token pembayaran tidak ditemukan. Silakan coba ulang.',
                confirmButtonColor: '#d32f2f'
            });
            return;
        }

        // Panggil Midtrans Snap popup
        snap.pay(snapToken, {
            onSuccess: function(result) {
                window.location.href = "{{ route('payment.success', ['booking_id' => $bookingId]) }}";
            },
            onPending: function(result) {
                console.log('PENDING', result);
            },
            onError: function(result) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Pembayaran gagal. Silakan coba lagi.',
                    confirmButtonColor: '#d32f2f'
                });
                console.error(result);
            }
        });
    });

    // Tombol “Batalkan Pesanan” menggunakan SweetAlert2 + AJAX
    document.getElementById('cancel-button')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: 'Anda yakin ingin membatalkan pesanan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d32f2f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('payment.cancel', ['booking_id' => $bookingId]) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (! res.ok) throw new Error('Network response was not ok');
                    return res.json().catch(() => null);
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Dibatalkan',
                        text: 'Pesanan Anda berhasil dibatalkan.',
                        confirmButtonColor: '#289A84'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat membatalkan pesanan.',
                        confirmButtonColor: '#d32f2f'
                    });
                    console.error(err);
                });
            }
        });
    });
</script>

{{-- Sertakan Midtrans Snap.js --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

@endsection
