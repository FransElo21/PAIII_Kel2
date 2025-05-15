@extends('layouts.owner.index-owner')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Detail Transaksi #{{ $booking->id }}</h2>
        <a href="{{ route('owner.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <img src="{{ $booking->property_image ? asset('storage/'.$booking->property_image) : asset('images/default-property.jpg') }}" 
                         class="img-fluid rounded" 
                         alt="{{ $booking->property_name }}">
                </div>
                <div class="col-md-8">
                    <h4>{{ $booking->property_name }}</h4>
                    <p class="text-muted">{{ $booking->property_address }}</p>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>Check-in:</strong><br>
                            {{ \Carbon\Carbon::parse($booking->check_in)->translatedFormat('l, j F Y') }}</p>
                            
                            <p><strong>Nama Tamu:</strong><br>
                            {{ $booking->guest_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Check-out:</strong><br>
                            {{ \Carbon\Carbon::parse($booking->check_out)->translatedFormat('l, j F Y') }}</p>
                            
                            <p><strong>Email:</strong><br>
                            {{ $booking->email }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded">
                        <div>
                            <span class="badge 
                                @if($booking->status == 'pending') bg-warning text-dark
                                @elseif($booking->status == 'confirmed') bg-success
                                @elseif($booking->status == 'completed') bg-info
                                @elseif($booking->status == 'cancelled') bg-danger
                                @else bg-secondary @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <h5 class="mb-0">Total: Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Detail Kamar</h5>
        </div>
        <div class="card-body">
            @if($rooms->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tipe Kamar</th>
                            <th>Jumlah</th>
                            <th>Harga per Malam</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
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
            @else
            <div class="alert alert-warning mb-0">
                Tidak ada detail kamar tersedia untuk transaksi ini.
            </div>
            @endif
        </div>
    </div>

    @if($booking->status == 'pending')
    <div class="d-flex justify-content-end gap-2 mt-4">
        <form action="{{ route('owner.bookings.approve', $booking->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle me-1"></i> Setujui
            </button>
        </form>
        <form action="{{ route('owner.bookings.reject', $booking->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-x-circle me-1"></i> Tolak
            </button>
        </form>
    </div>
    @endif
</div>
@endsection