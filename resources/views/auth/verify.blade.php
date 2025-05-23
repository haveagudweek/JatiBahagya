@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">{{ __('Verifikasi Akun') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p class="text-center">Kode OTP telah dikirim ke <strong>{{ $email ?? '' }}</strong></p>

                    <form method="POST" action="{{ route('verify-post') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email ?? '' }}">

                        <div class="mb-3">
                            <label for="otp" class="form-label">Kode OTP</label>
                            <input id="otp" type="text" 
                                   class="form-control @error('otp') is-invalid @enderror" 
                                   name="otp" required autofocus 
                                   placeholder="Masukkan 6 digit OTP"
                                   maxlength="6">
                            @error('otp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Verifikasi') }}
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <form method="POST" action="{{ route('resend.otp') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email ?? '' }}">
                            <button type="submit" class="btn btn-link">
                                Kirim Ulang OTP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection