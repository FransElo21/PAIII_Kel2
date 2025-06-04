@extends('layouts.owner.index-owner')
@section('content')

<style>
  /* Breadcrumb */
  .breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #94a3b8;
    font-weight: 600;
  }
  .breadcrumb-item a {
    color: #64748b;
    font-weight: 500;
    transition: color 0.3s ease;
  }
  .breadcrumb-item a:hover {
    color: #289A84; /* teal */
  }
  .breadcrumb-item.active {
    color: #289A84;
    font-weight: 700;
  }

  /* Card */
  .card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 12px 24px rgba(22, 160, 133, 0.1);
    transition: box-shadow 0.3s ease;
    background: #fff;
  }
  .card:hover {
    box-shadow: 0 16px 32px rgba(22, 160, 133, 0.15);
  }

  /* Booking Info */
  .booking-info img {
    border-radius: 1rem;
    box-shadow: 0 8px 16px rgba(0,0,0,0.08);
    transition: transform 0.3s ease;
  }
  .booking-info img:hover {
    transform: scale(1.05);
  }

  .booking-info h3 {
    font-weight: 700;
    color: #134e4a;
  }

  .booking-info p {
    color: #4b5563;
    font-size: 1rem;
    margin-bottom: 0.5rem;
  }
  .booking-info strong {
    color: #0f766e;
  }

  /* Status Badge */
  .status-badge {
    padding: 6px 18px;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: capitalize;
    box-shadow: 0 2px 8px rgba(22, 160, 133, 0.2);
    display: inline-block;
    min-width: 100px;
    text-align: center;
    color: white;
  }
  /* Status Colors */
  .status-Belum\ Dibayar {
    background-color: #fbbf24; /* amber-400 */
    color: #92400e;
    box-shadow: 0 2px 8px #fbbf24aa;
  }
  .status-Berhasil {
    background-color: #10b981; /* emerald-500 */
    color: white;
    box-shadow: 0 2px 8px #10b981aa;
  }
  .status-Dibatalkan {
    background-color: #ef4444; /* red-500 */
    color: white;
    box-shadow: 0 2px 8px #ef4444aa;
  }
  .status-Selesai {
    background-color: #3b82f6; /* blue-500 */
    color: white;
    box-shadow: 0 2px 8px #3b82f6aa;
  }

  /* Table */
  .table {
    border-collapse: separate;
    border-spacing: 0 0.75rem;
  }
  .table thead tr th {
    background: #e0f2f1;
    color: #0f766e;
    border: none;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }
  .table tbody tr {
    background: #f9fafb;
    border-radius: 1rem;
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.04);
    transition: box-shadow 0.3s ease;
  }
  .table tbody tr:hover {
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.08);
  }
  .table tbody tr td {
    vertical-align: middle;
    border-top: none;
    border-bottom: none;
    padding: 1rem 1.25rem;
    color: #374151;
  }

  /* Responsive */
  @media (max-width: 767.98px) {
    .booking-info h3 {
      font-size: 1.5rem;
    }
    .booking-info p {
      font-size: 0.95rem;
    }
    .status-badge {
      min-width: 90px;
      font-size: 0.85rem;
      padding: 5px 14px;
    }
    .table tbody tr td {
      padding: 0.75rem 0.75rem;
      font-size: 0.9rem;
    }
  }
</style>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/property">Pemesanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Pemesanan</li>
  </ol>
</nav>

<div class="container">

  {{-- Booking Info --}}
  <div class="card booking-info mb-5 p-4">
    <div class="row align-items-center">
      <div class="col-md-4 text-center mb-4 mb-md-0">
        <img 
          src="{{ $booking->property_image 
                   ? asset('storage/'.$booking->property_image) 
                   : asset('assets/images/property.jpg') }}"
          alt="{{ $booking->property_name }}"
          class="img-fluid"
          style="max-height:220px; object-fit:cover;"
        >
      </div>
      <div class="col-md-8">
        <h3>{{ $booking->property_name }}</h3>
        <p><strong>Alamat:</strong> {{ $booking->property_address }}</p>
        <p>
          <strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('j M Y') }}  
          &mdash;  
          <strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('j M Y') }}
        </p>
        <p><strong>Penyewa:</strong> {{ $booking->guest_name }} ({{ $booking->email }})</p>
        <p><strong>NIK:</strong> {{ $booking->nik }}</p>
        <p>
          <strong>Status:</strong>
          <span class="status-badge status-{{ $booking->status }}">
            {{ ucfirst($booking->status) }}
          </span>
        </p>
        <p><strong>Total Harga:</strong>  
          Rp {{ number_format($booking->total_price, 0, ',', '.') }}
        </p>
      </div>
    </div>
  </div>

  {{-- Detail Kamar --}}
  <div class="card p-4">
    <h4 class="mb-4" style="color: #0f766e; font-weight: 700;">Detail Kamar</h4>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Tipe Kamar</th>
            <th>Qty</th>
            <th>Harga / Kamar</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rooms as $i => $room)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $room->room_type }}</td>
              <td>{{ $room->quantity }}</td>
              <td>Rp {{ number_format($room->price_per_room, 0, ',', '.') }}</td>
              <td>Rp {{ number_format($room->subtotal, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>

@endsection
