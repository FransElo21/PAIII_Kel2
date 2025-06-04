@extends('layouts.owner.index-owner')
@section('content')

{{-- Welcome Card with Gradient --}}
<div class="card w-100 overflow-hidden rounded-5 shadow-sm mb-4 border-0">
    <div class="card-body position-relative p-4" style="background: linear-gradient(135deg, #152C5B, #289A84);">
        <div class="row align-items-center text-white">
            <div class="col-md-7">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('owner/assets/images/avatars/default.png') }}" 
                         class="rounded-circle shadow-sm" width="60" height="60" alt="user">
                    <div>
                        <p class="mb-0 fw-light">Selamat Datang Kembali!!</p>
                        <h4 class="fw-bold mb-0 text-white">{{ Auth::user()->name }}!</h4>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-4">
                    {{-- Pendapatan Bulan Ini --}}
                    <div>
                        <h5 class="mb-1 fw-semibold d-flex align-items-center text-white">
                            Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}
                            <i class="bi bi-arrow-up-right-circle-fill text-success ms-2"></i>
                        </h5>
                        <small>Pendapatan Bulan Ini</small>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-grd-success" role="progressbar" style="width: 100%" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    {{-- Total Pendapatan --}}
                    <div>
                        <h5 class="mb-1 fw-semibold d-flex align-items-center text-white">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            <i class="bi bi-arrow-up-right-circle-fill text-success ms-2"></i>
                        </h5>
                        <small>Total Pendapatan</small>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-grd-success" role="progressbar" style="width: 100%" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 text-end d-none d-md-block">
                <img src="{{ asset('owner/assets/images/gallery/welcome-back-3.png') }}" height="180" alt="Welcome Image">
            </div>
        </div>
    </div>
</div>

{{-- Summary Cards with Hover Effects --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100 rounded-4 border-0 transition-hover hover-lift">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                    <i class="bi bi-house-door-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $propertyCount }}</h5>
                    <small class="text-muted">Total Properties</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm h-100 rounded-4 border-0 transition-hover hover-lift">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                    <i class="bi bi-journal-bookmark-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $bookingCount }}</h5>
                    <small class="text-muted">Total Pemesanan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm h-100 rounded-4 border-0 transition-hover hover-lift">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $pendingApprovalCount }}</h5>
                    <small class="text-muted">Menunggu Pembayaran</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart Area --}}
<div class="card shadow-sm rounded-4 p-4 mb-4 border-0">
    <h5 class="mb-3 fw-semibold">Tren Pemesanan Bulanan</h5>
    <canvas id="salesChart" height="100"></canvas>
</div>

{{-- Recent Bookings Table --}}
<div class="card shadow-sm rounded-4 p-4 border-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-semibold">Pemesanan Terbaru</h5>
        <a href="{{ route('pemilik.riwayat-transaksi') }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill">
            Lihat Semua
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#ID Booking</th>
                    <th>Property</th>
                    <th>Tamu</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentBookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->property_name }}</td>
                    <td>{{ $booking->guest_name ?? $booking->user_name }}</td>
                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = match($booking->status) {
                                'pending', 'Belum Dibayar' => 'warning',
                                'confirmed', 'Berhasil' => 'success',
                                'cancelled', 'Dibatalkan' => 'danger',
                                'Kadaluarsa' => 'secondary',
                                'Selesai' => 'info',
                                default => 'dark'
                            };
                        @endphp
                        <span class="badge bg-{{ $statusClass }} text-white rounded-pill px-3 py-1">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</td>
                    <td>
                        <a 
                            href="{{ route('owner.bookings.detail', $booking->id) }}" 
                            class="btn btn-sm btn-info rounded-circle" 
                            title="Detail"
                        >
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Tidak ada pemesanan terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Custom CSS for Modern Look --}}
<style>
    .transition-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .rounded-pill {
        border-radius: 50rem !important;
    }
</style>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> 

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 

{{-- Chart Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlySalesLabels) !!},
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: {!! json_encode($monthlySalesData) !!},
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#4e73df'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#fff',
                        titleColor: '#333',
                        bodyColor: '#555',
                        borderColor: '#ddd',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>

@endsection