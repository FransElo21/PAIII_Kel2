@extends('layouts.owner.index-owner')
@section('content')

<div class="container py-4">

  {{-- Booking Info --}}
  <div class="card mb-4 p-4">
    <div class="row">
      <div class="col-md-4 text-center">
        <img 
          src="{{ $booking->property_image 
                   ? asset('storage/'.$booking->property_image) 
                   : asset('assets/images/property.jpg') }}"
          alt="{{ $booking->property_name }}"
          class="img-fluid rounded mb-3"
          style="max-height:200px; object-fit:cover;"
        >
      </div>
      <div class="col-md-8">
        <h3 class="mb-2">{{ $booking->property_name }}</h3>
        <p class="mb-1"><strong>Alamat:</strong> {{ $booking->property_address }}</p>
        <p class="mb-1">
          <strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('j M Y') }}  
          &mdash;  
          <strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('j M Y') }}
        </p>
        <p class="mb-1"><strong>Penyewa:</strong> {{ $booking->guest_name }} ({{ $booking->email }})</p>
        <p class="mb-1"><strong>NIK:</strong> {{ $booking->nik }}</p>
        <p class="mb-1">
          <strong>Status:</strong>
          <span class="status-badge status-{{ $booking->status }}">
            {{ ucfirst($booking->status) }}
          </span>
        </p>
        <p class="mb-0"><strong>Total Harga:</strong>  
          Rp {{ number_format($booking->total_price, 0, ',', '.') }}
        </p>
      </div>
    </div>
  </div>

  {{-- Detail Kamar --}}
  <div class="card p-4">
    <h4 class="mb-3">Detail Kamar</h4>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-light">
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
