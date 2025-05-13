@extends('layouts.index-welcome')
@section('content')

<style>
    /* Sidebar styling */
    .sidebar {
        background-color: #f1f3f5;
        border-right: 1px solid #dee2e6;
        padding: 2rem 1rem;
        min-height: 100vh;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
        color: #495057;
        text-decoration: none;
        font-weight: 500;
    }

    .menu-item:hover,
    .menu-item.active {
        background-color: #28a745;
        color: white !important;
    }

    .menu-item i {
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }

    .menu-item:hover i {
        transform: scale(1.1);
    }

    /* Transaction Card Styling */
    .transaction-card {
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: #fff;
    }

    .transaction-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .transaction-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-top-left-radius: 15px;
        border-bottom-left-radius: 15px;
    }

    .transaction-details {
        padding: 1.5rem;
    }

    .card-title {
        font-weight: 600;
        color: #333;
    }

    .card-text strong {
        color: #6c757d;
    }

    /* Status badge styling */
    .transaction-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 10;
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 5px;
        font-weight: 500;
        text-transform: capitalize;
        color: white;
    }

    .transaction-status.pending {
        background-color: #ffc107;
    }

    .transaction-status.confirmed,
    .transaction-status.paid {
        background-color: #28a745;
    }

    .transaction-status.completed {
        background-color: #17a2b8;
    }

    .transaction-status.cancelled {
        background-color: #dc3545;
    }

    .transaction-status.expired {
        background-color: #6c757d;
    }

    /* Filter Button Styling */
    .filter-btn {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        border: 1px solid transparent;
        background-color: #e9ecef;
        color: #495057;
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .filter-btn.active,
    .filter-btn:hover {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }

    .filter-btn:not(.active):hover {
        background-color: #ced4da;
    }

    /* Search Box */
    .search-container input[type="text"] {
        border-radius: 25px;
        border: 1px solid #ccc;
        padding-left: 15px;
    }

    .search-container button {
        border-radius: 25px;
        border: 1px solid #ced4da;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .sidebar {
            min-height: auto;
            border-bottom: 1px solid #dee2e6;
        }
    }
</style>

<style>
    .filter-btn {
        display: inline-block;
        padding: 8px 20px;
        margin-right: 10px;
        border-radius: 30px;
        background-color: #e9ecef;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .filter-btn.active,
    .filter-btn:hover {
        background-color: #28a745;
        color: white;
    }
</style>

<!-- Main Content -->
<div class="container mt-2 mb-5">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="{{ route('profileuser.show') }}" 
                       class="menu-item {{ request()->routeIs('profileuser.show') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i> Profil Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('riwayat-transaksi.index') }}" 
                       class="menu-item {{ request()->routeIs('riwayat-transaksi.index') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat Transaksi
                    </a>
                </li>
            </ul>
        </div>

        <!-- Transaction History -->
        <div class="col-md-9">
            <h4 class="mb-4">Riwayat Transaksi</h4>

            <!-- Search & Filters -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <form class="d-flex gap-2 search-container">
                    <input type="text" class="form-control w-auto me-2" placeholder="Cari Pesanan..." />
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <div class="mb-4">
                    <a href="{{ route('riwayat-transaksi.index') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">
                        Semua
                    </a>
                    <a href="{{ route('riwayat-transaksi.index', ['status' => 'pending']) }}" class="filter-btn {{ request('status') == 'pending' ? 'active' : '' }}">
                        Belum Dibayar
                    </a>
                    <a href="{{ route('riwayat-transaksi.index', ['status' => 'paid']) }}" class="filter-btn {{ request('status') == 'paid' ? 'active' : '' }}">
                        Sudah Bayar
                    </a>
                    <a href="{{ route('riwayat-transaksi.index', ['status' => 'cancelled']) }}" class="filter-btn {{ request('status') == 'cancelled' ? 'active' : '' }}">
                        Dibatalkan
                    </a>
                </div>
            </div>

            <!-- Transaction Cards -->
            @foreach ($bookings as $booking)
                <div class="card mb-4 shadow-sm border rounded-3 overflow-hidden position-relative p-3">
                <!-- Tanggal -->
                <div class="text-muted small mb-2">13 juna</div>

                <div class="d-flex flex-column flex-md-row gap-3 align-items-start">
                    <!-- Gambar -->
                    <div style="flex: 0 0 160px;">
                        <img src="{{ $booking->property_image ? asset('storage/'.$booking->property_image) : asset('assets/images/property.jpg') }}" 
                            alt="Image" class="img-fluid rounded-3" style="object-fit: cover; height: 120px; width: 160px;">
                    </div>

                    <!-- Info -->
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1">{{ $booking->property_name }}</h6>
                        <p class="mb-1 text-muted small">{{ $booking->property_address }}</p>
                        <p class="mb-1 small">Durasi: {{ \Carbon\Carbon::parse($booking->check_in)->diffInMonths(\Carbon\Carbon::parse($booking->check_out)) }} Bulan 
                            ({{ \Carbon\Carbon::parse($booking->check_in)->translatedFormat('M') }} - {{ \Carbon\Carbon::parse($booking->check_out)->translatedFormat('M Y') }})</p>

                        <p class="mb-0 fw-semibold mt-2">
                            Total: <span class="text-dark">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </p>
                    </div>

                    <!-- Badge Status -->
                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                        @php
                            $statusClass = match($booking->status) {
                                'pending' => 'bg-warning text-dark',
                                'paid' => 'bg-success',
                                'completed' => 'bg-info',
                                'cancelled' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill text-uppercase" style="font-size: 0.75rem;">
                            {{ $booking->status === 'pending' ? 'Belum Dibayar' : ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex flex-wrap gap-2 mt-3 justify-content-end">
                    <a href="{{ route('riwayat-transaksi.detail', $booking->booking_id) }}" 
                    class="btn btn-success btn-sm px-4 rounded-pill">Lihat Detail</a>

                    <a href="#" class="btn btn-outline-secondary btn-sm px-4 rounded-pill">Hubungi Pemilik</a>

                    @php
                        $checkOutDate = \Carbon\Carbon::parse($booking->check_out);
                        $isCheckOutCompleted = $checkOutDate->isPast() && $booking->status === 'completed';
                    @endphp

                    @if ($isCheckOutCompleted && !$booking->reviewed)
                        <a href="{{ route('review.create', ['booking_id' => $booking->booking_id]) }}" 
                        class="btn btn-outline-success btn-sm px-4 rounded-pill">
                            <i class="bi bi-chat-left-text me-1"></i> Berikan Ulasan
                        </a>
                    @endif
                </div>

            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons @1.10.5/font/bootstrap-icons.css">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap @5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection