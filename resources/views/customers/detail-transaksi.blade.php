@extends('layouts.index-welcome')
@section('content')

<style>
  :root {
    --bg-light: #f4f6f8;
    --card-bg: #ffffff;
    --primary: #289A84;
    --muted: #6c757d;
    --radius: 1rem;
    --shadow-sm: 0 4px 20px rgba(0, 0, 0, 0.05);
    --shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.08);
  }

  body {
    background: var(--bg-light);
  }

  .breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 1.5rem;
  }
  .breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: var(--muted);
  }
  .breadcrumb-item a {
    color: var(--muted);
    transition: color .2s;
  }
  .breadcrumb-item a:hover {
    color: var(--primary);
  }
  .breadcrumb-item.active {
    color: var(--primary);
    font-weight: 600;
  }

  .booking-detail-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: transform .3s, box-shadow .3s;
  }
  .booking-detail-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
  }

  .booking-image {
    height: 100%;
    object-fit: cover;
  }

  .booking-info {
    padding: 2rem;
  }
  .booking-info h5 {
    font-weight: 600;
    margin-bottom: .75rem;
    color: #333;
  }
  .booking-info p {
    margin-bottom: .5rem;
    color: #555;
  }

  .badge-status {
    display: inline-block;
    padding: .4em .9em;
    font-size: .75rem;
    text-transform: uppercase;
    border-radius: 50px;
    font-weight: 600;
    transition: background .2s;
  }
  .badge-pending   { background: #ffd966; color: #212529; }
  .badge-paid      { background: #28a745; color: #fff; }
  .badge-completed { background: #17a2b8; color: #fff; }
  .badge-cancelled { background: #dc3545; color: #fff; }
  .badge-expired   { background: #6c757d; color: #fff; }

  /* room-card */
  .room-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
  }
  .room-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
  }
  .room-card-body {
    padding: 1.5rem;
  }
  .room-card-body h6 {
    font-weight: 600;
    margin-bottom: .5rem;
  }
  .room-card-body .meta {
    color: var(--muted);
    font-size: .9rem;
    margin-bottom: .75rem;
  }
  .room-card-body .price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #d63384;
  }

  .btn-back {
    border-radius: 50px;
    padding: .5rem 1.5rem;
    transition: background .2s, transform .2s;
  }
  .btn-back:hover {
    background: var(--primary);
    color: #fff;
    transform: translateY(-2px);
  }
</style>

<div class="container py-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb px-0">
      <li class="breadcrumb-item"><a href="{{ route('landingpage') }}">Beranda</a></li>
      <li class="breadcrumb-item"><a href="#">{{ $booking->property_name }}</a></li>
      <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>
  </nav>

  <h4 class="mb-4 fw-semibold">Detail Transaksi</h4>

  <div class="card booking-detail-card mb-5">
    <div class="row g-0">
      <div class="col-md-4">
        <img
          src="{{ $booking->property_image ? asset('storage/'.$booking->property_image) : asset('assets/images/property.jpg') }}"
          alt="{{ $booking->property_name }}"
          class="w-100 booking-image">
      </div>
      <div class="col-md-8">
        <div class="booking-info">
          <h5>{{ $booking->property_name }}</h5>
          <p><i class="bi bi-geo-alt-fill me-1"></i>
            <small>{{ $booking->property_address }}</small>
          </p>
          <p><strong>Check-in:</strong>
            {{ \Carbon\Carbon::parse($booking->check_in)->translatedFormat('j M Y') }}
          </p>
          <p><strong>Check-out:</strong>
            {{ \Carbon\Carbon::parse($booking->check_out)->translatedFormat('j M Y') }}
          </p>
          <p><strong>Total Harga:</strong>
            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
          </p>
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
  <div class="row g-4 mb-4">
    @foreach ($rooms as $room)
      <div class="col-md-6">
        <div class="room-card h-100">
          <div class="room-card-body">
            <h6>{{ $room['room_type'] }}</h6>
            <div class="meta">
              <small>Jumlah: {{ $room['quantity'] }} kamar</small>
              <br>
              <small>Harga/malam: Rp {{ number_format($room['price_per_room'], 0, ',', '.') }}</small>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="price">Rp {{ number_format($room['subtotal'], 0, ',', '.') }}</span>
              <i class="bi bi-door-open fs-3 text-muted"></i>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <a href="{{ route('riwayat-transaksi.index') }}"
     class="btn btn-outline-secondary btn-back">
    <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
  </a>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

@endsection
