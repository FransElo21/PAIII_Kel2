@extends('layouts.index-welcome')
@section('content')

<style>
    .booking-detail-card {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
        background-color: #fff;
        transition: 0.3s ease;
    }

    .booking-detail-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .booking-image {
        object-fit: cover;
        height: 100%;
        border-top-left-radius: 16px;
        border-bottom-left-radius: 16px;
    }

    .booking-info h5 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .booking-info p {
        margin-bottom: 0.4rem;
        font-size: 0.95rem;
        color: #555;
    }

    .badge-status {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 20px;
    }

    .badge-pending { background: #ffc107; color: #212529; }
    .badge-paid { background: #28a745; color: white; }
    .badge-completed { background: #17a2b8; color: white; }
    .badge-cancelled { background: #dc3545; color: white; }
    .badge-expired { background: #6c757d; color: white; }

    .table th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .btn-back {
        border-radius: 25px;
        padding: 8px 24px;
    }
     /* Breadcrumb */
        .breadcrumb {
            /* padding: 0.75rem 1.25rem; */
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); */
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
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('landingpage') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="#">Property</a></li>
            <li class="breadcrumb-item active" aria-current="page"></li>
        </ol>

    <div class="container mb-5">
        <h4 class="mb-4 fw-semibold">Detail Transaksi</h4>

        <div class="card mb-5 booking-detail-card">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{ $booking->property_image ? asset('storage/'.$booking->property_image) : asset('assets/images/property.jpg') }}" 
                        alt="{{ $booking->property_name }}" 
                        class="img-fluid booking-image w-100">
                </div>
                <div class="col-md-8">
                    <div class="card-body booking-info">
                        <h5>{{ $booking->property_name }}</h5>
                        <p><i class="bi bi-geo-alt-fill me-1 text-muted"></i> {{ $booking->property_address }}</p>
                        <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->translatedFormat('j M Y') }}</p>
                        <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->translatedFormat('j M Y') }}</p>
                        <p><strong>Total Harga:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                        <p>
                            <strong>Status:</strong>
                            <span class="badge-status badge-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-3 fw-semibold">Detail Kamar</h5>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover table-bordered align-middle mb-4">
                <thead>
                    <tr>
                        <th>Tipe Kamar</th>
                        <th>Jumlah</th>
                        <th>Harga per Kamar</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                        <tr>
                            <td>{{ $room['room_type'] }}</td>
                            <td>{{ $room['quantity'] }}</td>
                            <td>Rp {{ number_format($room['price_per_room'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($room['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <a href="{{ route('riwayat-transaksi.index') }}" class="btn btn-secondary btn-back mt-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
    </div>
</div>

@endsection
