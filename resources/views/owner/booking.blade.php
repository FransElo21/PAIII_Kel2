@extends('layouts.owner.index-owner')
@section('content')

<style>
    /* Table Styling */
    .table-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table th, .table td {
        vertical-align: middle !important;
        text-align: center;
    }

    .table img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-pending { background-color: #ffc107; color: #000; }
    .status-paid { background-color: #28a745; color: #fff; }
    .status-cancelled { background-color: #dc3545; color: #fff; }
    .status-completed { background-color: #17a2b8; color: #fff; }

    .action-buttons a {
        margin-right: 5px;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
    }
</style>

<div class="container mt-4 mb-5">
    <div class="row g-4">
        <div class="card mt-4">
            <div class="card-body">
                <div class="product-table">
                    <div class="table-responsive white-space-nowrap">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th><input class="form-check-input" type="checkbox"></th>
                                    <th>Properti</th>
                                    <th>Nama Penyewa</th>
                                    <th>Tanggal</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @if ($bookings && count($bookings) > 0)
                                        @foreach ($bookings as $index => $booking)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="d-flex align-items-center">
                                                    <img src="{{ $booking->property_image ? asset('storage/'.$booking->property_image) : asset('assets/images/property.jpg') }}"
                                                        alt="{{ $booking->property_name }}">
                                                    <div class="ms-3">
                                                        <div class="fw-bold">{{ $booking->property_name }}</div>
                                                    </div>
                                                </td>
                                                <td>{{ $booking->guest_name }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('j M') }} - 
                                                    {{ \Carbon\Carbon::parse($booking->check_out)->format('j M Y') }}
                                                </td>
                                                <td>Rp {{ number_format((int)$booking->total_price, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="status-badge 
                                                        @switch($booking->status)
                                                            @case('pending') status-pending @break
                                                            @case('paid') status-paid @break
                                                            @case('completed') status-completed @break
                                                            @case('cancelled') status-cancelled @break
                                                            @default status-expired
                                                        @endswitch">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                                <td class="action-buttons">
                                                    <!-- Tombol Edit (Menuju Halaman Edit) -->
                                                    <a href="" class="btn btn-warning btn-sm rounded-circle" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="bi bi-receipt fs-4 text-muted"></i>
                                                <p class="mt-2 mb-0 text-muted">Belum ada transaksi.</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination (jika diperlukan) -->
            </div>
        </div>
    </div>
</div>

@endsection