@extends('layouts.app')
@section('content')
<div class="container text-center mt-5">
    <h2>Pembayaran Berhasil!</h2>
    <p>Booking ID: {{ $bookingId }}</p>
    <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection