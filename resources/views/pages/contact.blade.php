@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        {{-- Tombol Back (Optional, if you want a back button) --}}
        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>

        <div class="row justify-content-center">  {{-- Center the content --}}
            <div class="col-md-12"> {{-- Adjust column width for desired size --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Hubungi Kami</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Silakan isi formulir di bawah ini untuk menghubungi kami. Kami akan segera merespon pertanyaan atau pesan Anda.
                        </p>

                        <form action="#" method="POST"> 
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                        </form>

                        {{-- Display success/error messages (if any) --}}
                        @if (session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mt-3">
                                {{ session('error') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection