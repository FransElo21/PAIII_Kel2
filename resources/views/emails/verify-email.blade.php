<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email</title>
</head>
<body>
    <h3>Halo, {{ $username }}</h3>
    <p>Klik tombol di bawah untuk memverifikasi email Anda:</p>

    <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
        Verifikasi Email
    </a>

    <p>Jika tombol di atas tidak berfungsi, salin tautan ini ke browser:</p>
    <p>{{ $verificationUrl }}</p>
</body>
</html>