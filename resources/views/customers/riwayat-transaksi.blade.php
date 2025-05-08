@extends('layouts.index-welcome')
@section('content')
<style>
    /* Sidebar styling */
    .sidebar {
        background-color: #f8f9fa;
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
    }

    .menu-item:hover,
    .menu-item.active {
        background-color: #28a745;
        color: white !important;
        font-weight: 500;
    }

    .menu-item i {
        font-size: 1.1rem;
    }

    /* Riwayat Transaksi Styling */
    .transaction-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .transaction-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .transaction-details {
        padding: 15px;
    }

    .transaction-status {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        text-transform: capitalize;
    }

    .transaction-status.pending {
        background-color: #ffc107;
    }

    .transaction-status.cancelled {
        background-color: #dc3545;
    }

    .transaction-actions button {
        margin-right: 10px;
    }

    @media (max-width: 768px) {
        .sidebar {
            border-right: none;
            border-bottom: 1px solid #dee2e6;
            min-height: auto;
        }
    }
    /* Transaction Card Styling */
    .transaction-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .transaction-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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
        background-color: #fff;
    }

    .card-title {
        font-weight: 600;
        color: #333;
    }

    .card-text strong {
        color: #6c757d;
    }

    .transaction-status {
        z-index: 1;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        text-transform: capitalize;
        color: white;
    }

    .transaction-status.pending {
        background-color: #ffc107;
    }
    .transaction-status.confirmed {
        background-color: #28a745;
    }
    .transaction-status.completed {
        background-color: #20c997;
    }
    .transaction-status.cancelled {
        background-color: #dc3545;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #218838;
    }

    .filter-btn {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        background-color: transparent;
        border: 1px solid #e0e0e0;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .filter-btn.active,
    .filter-btn:hover {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }

    /* Optional: Hover effect */
    .filter-btn:not(.active):hover {
        background-color: #e0e0e0;
    }
    /* Jarak antar tombol */
    .d-flex.gap-3 > * {
        margin-right: 1rem !important;
        margin-bottom: 0.5rem !important;
    }
</style>

<!-- Main Content -->
<div class="container">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('profileuser.show') }}" class="menu-item {{ request()->routeIs('profileuser.show') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i> Profil Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('riwayat-transaksi.index') }}" class="menu-item {{ request()->routeIs('riwayat-transaksi.index') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat Transaksi
                    </a>
                </li>
            </ul>
        </div>

        <!-- Transaction History -->
        <div class="col-md-9">
            <h4>Riwayat Transaksi</h4>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <form class="d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Cari Pesanan" />
                    <button class="btn btn-outline-secondary">Cari</button>
                </form>
                {{-- <div class="d-flex gap-2">
                    <input type="date" class="form-control" placeholder="Dari" />
                    <input type="date" class="form-control" placeholder="Sampai" />
                </div> --}}
            </div>

            <!-- Filters -->
            <div class="mb-3 d-flex flex-wrap gap-3">
                <a href="#" class="filter-btn active">Semua</a>
                <a href="#" class="filter-btn">Belum Dibayar</a>
                <a href="#" class="filter-btn">Dikonfirmasi</a>
                <a href="#" class="filter-btn">Selesai</a>
                <a href="#" class="filter-btn">Dibatalkan</a>
            </div>

            <!-- Transaction Cards -->
            @foreach ($bookings as $booking)
            <div class="card transaction-card mb-3 shadow-sm position-relative">
                <div class="row g-0">
                    <div class="col-md-4">
                        <!-- Gambar properti -->
                        <img src="{{ $booking->property_image ? asset('storage/'.$booking->property_image) : asset('assets/images/property.jpg') }}" 
                            alt="{{ $booking->property_name }}" 
                            class="img-fluid rounded-start h-100 object-fit-cover">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body transaction-details p-4">
                            <!-- Nama properti -->
                            <h5 class="card-title mb-2">{{ $booking->property_name }}</h5>
                            
                            <!-- Alamat properti -->
                            <p class="card-text mb-1">{{ $booking->property_address }}</p>
                            
                            <!-- Tanggal check-in/check-out -->
                            <p class="card-text mb-1">
                                {{ \Carbon\Carbon::parse($booking->check_in)->format('j M') }} 
                                - 
                                {{ \Carbon\Carbon::parse($booking->check_out)->format('j M') }}
                            </p>
                            
                            <!-- Total harga -->
                            <p class="card-text mb-1">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            
                            <!-- Tombol detail -->
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-success">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Status booking -->
                <span class="transaction-status position-absolute top-0 end-0 m-2 
                    {{ $booking->status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
        @endforeach
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection