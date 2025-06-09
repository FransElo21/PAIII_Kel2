<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Kata Sandi</title>
  <style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
    .container { background: #ffffff; width: 100%; max-width: 600px; margin: 40px auto; padding: 20px; border-radius: 8px; }
    .btn { display: inline-block; padding: 12px 20px; background: #289A84; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
    .footer { font-size: 12px; color: #777; margin-top: 20px; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Reset Kata Sandi</h2>
    <p>Halo {{ $user->name }},</p>
    <p>Kami menerima permintaan untuk mereset kata sandi akun Anda. Klik tombol di bawah ini untuk membuat kata sandi baru:</p>
    <p style="text-align:center;">
      <a href="{{ $link }}" class="btn">Reset Kata Sandi</a>
    </p>
    <p>Link ini akan kedaluwarsa dalam 1 jam.</p>
    <p>Jika Anda tidak meminta reset kata sandi, abaikan email ini.</p>
    <hr>
    <p class="footer">Tim Hommie &mdash; &copy; {{ date('Y') }}</p>
  </div>
</body>
</html>
