<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Pembayaran Booking #{{ $booking->id ?? $booking->booking_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #222;
            background: #fff;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #289A84;
            padding: 16px 0 10px 0;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-left {
            flex: 1;
        }
        .header h2 {
            color: #289A84;
            margin: 0 0 5px 0;
        }
        .logo-container {
            width: 95px;
            height: 60px;
            text-align: right;
        }
        .logo-container img {
            max-width: 95px;
            max-height: 60px;
        }
        .section-title {
            background: #e8f7f4;
            color: #289A84;
            padding: 5px 12px;
            border-radius: 4px;
            margin-top: 24px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 15px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .table th, .table td {
            border: 1px solid #d6eae3;
            padding: 7px 9px;
            text-align: left;
        }
        .table th {
            background: #f6fbfa;
            color: #222;
        }
        .total {
            font-weight: bold;
            font-size: 15px;
            color: #d32f2f;
        }
        .booking-meta {
            font-size: 13px;
            margin-bottom: 12px;
        }
        .footer {
            margin-top: 28px;
            border-top: 1px dashed #a2c8bc;
            padding-top: 8px;
            font-size: 11px;
            color: #777;
        }
        .mt-2 { margin-top: 12px; }
        .mb-2 { margin-bottom: 12px; }
        .w-50 { width: 50%; }
        .w-100 { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h2>Resi Pembayaran Booking</h2>
            <div class="booking-meta">
                <strong>Booking ID:</strong> #{{ $booking->id ?? $booking->booking_id }}<br>
                <strong>Tanggal:</strong> {{ date('d F Y, H:i', strtotime($booking->created_at ?? now())) }}
            </div>
        </div>
        <div class="logo-container">
            <img src="{{ public_path('assets/images/newLogohommie.png') }}" alt="Logo" />
        </div>
    </div>
    
    <div>
        <div class="section-title">Detail Pemesan</div>
        <table class="table w-100">
            <tr>
                <th>Nama Pemesan</th>
                <td>{{ $booking->guest_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $booking->email ?? $booking->guest_email }}</td>
            </tr>
            <tr>
                <th>NIK</th>
                <td>{{ $booking->nik }}</td>
            </tr>
        </table>

        <div class="section-title">Detail Properti</div>
        <table class="table w-100">
            <tr>
                <th>Nama Properti</th>
                <td>{{ $booking->property_name ?? $booking->property }}</td>
            </tr>
            <tr>
                <th>Check-in</th>
                <td>{{ \Carbon\Carbon::parse($booking->check_in)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <th>Check-out</th>
                <td>{{ \Carbon\Carbon::parse($booking->check_out)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <th>Pemilik Properti</th>
                <td>{{ $booking->owner_name ?? '-' }} ({{ $booking->owner_email ?? '-' }})</td>
            </tr>
        </table>

        <div class="section-title">Kamar Dipesan</div>
        <table class="table w-100">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tipe Kamar</th>
                    <th>Jumlah</th>
                    <th>Harga/malam</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            @foreach($rooms as $i => $room)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $room->room_type }}</td>
                    <td>{{ $room->quantity }}</td>
                    <td>Rp{{ number_format($room->price_per_room, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($room->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right total">Total Bayar</th>
                    <th class="total">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="footer text-center">
            Terima kasih telah melakukan pemesanan.<br>
            Resi ini sah tanpa tanda tangan.<br>
            Dicetak otomatis melalui sistem Hommie.
        </div>
    </div>
</body>
</html>
