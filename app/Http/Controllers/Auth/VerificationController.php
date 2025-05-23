<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTP;

class VerificationController extends Controller
{
    // Tampilkan form verifikasi
    public function showVerificationForm(Request $request)
    {
        return view('auth.verify', [
            'email' => $request->session()->get('email')
        ]);
    }

    // Proses verifikasi OTP
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$user) {
            return redirect()->route('verify')
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Kode OTP tidak valid']);
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            return redirect()->route('verify')
                ->withInput($request->only('email'))
                ->withErrors(['otp' => 'Kode OTP sudah kadaluarsa']);
        }

        $user->update([
            'is_verified' => true,
            'otp' => null,
            'otp_expires_at' => null
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil diverifikasi! Silakan login');
    }

    // Kirim ulang OTP
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        if ($user->is_verified) {
            return redirect()->route('login')
                ->with('info', 'Akun sudah terverifikasi');
        }

        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(30)
        ]);

        Mail::to($user->email)->send(new SendOTP($otp));

        return back()->with('success', 'Kode OTP baru telah dikirim');
    }
}
