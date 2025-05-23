@extends('layouts.app')

@section('content')
    <div class="container mt-4 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg border-0 text-center p-4" style="max-width: 600px; width: 100%;">
            <div class="card-body">
                {{-- Ikon centang animasi --}}
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="currentColor" class="text-success bi bi-check-circle-fill bounce-in" viewBox="0 0 16 16">
                        <path d="M16 8a8 8 0 1 1-16 0 8 8 0 0 1 16 0zM7.468 10.94l4.95-4.95-1.06-1.06-4.95 4.95-2.47-2.47-1.06 1.06 3.53 3.53z"/>
                    </svg>
                </div>

                <h3 class="fw-bold text-success">Pesanan Berhasil!</h3>
                <p class="text-muted">Terima kasih telah berbelanja. Pesanan Anda sedang diproses.</p>

                <a href="{{ route('orders.index') }}" class="btn btn-primary w-100 mt-3">Kembali ke Daftar Pesanan</a>
            </div>
        </div>
    </div>

    <style>
        /* Animasi masuk */
        .bounce-in {
            animation: bounceIn 0.8s ease;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.1); opacity: 0.9; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
@endsection
