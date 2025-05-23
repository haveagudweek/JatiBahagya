<!DOCTYPE html>
<html>

<head>
    <title>Verifikasi Email</title>
</head>

<body>
    <h2>Verifikasi Akun Anda</h2>
    <p>Gunakan kode OTP berikut untuk verifikasi akun:</p>
    <h3 style="color: #2563eb; font-size: 24px;">{{ $otp }}</h3>
    <p>Kode ini berlaku selama {{ $expiryMinutes }} menit.</p>
    <p>Jika Anda tidak meminta ini, abaikan email ini.</p>
</body>

</html>
