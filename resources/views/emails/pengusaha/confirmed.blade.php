@component('mail::message')
# Halo {{ $name }},

Akun pengusaha Anda telah berhasil dikonfirmasi oleh admin.

Anda sekarang dapat menggunakan semua fitur pengusaha di platform kami.

@component('mail::button', ['url' => route('login'), 'color' => 'primary'])
Masuk ke Halaman Login
@endcomponent

Terima kasih telah bergabung dan mempercayai layanan kami.

Salam,<br>
**Tim Hommie**
@endcomponent
