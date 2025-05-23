@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <!-- Sidebar -->
        <div class="col-md-3 mb-3">
            @include('components.sidebar')
        </div>

        <!-- Change Password Section -->
        <div class="col-md-9 mb-3">
            <h5 class="mb-2">Kata Sandi</h5>
            <div class="card">
                <div class="card-header">Ganti Kata Sandi</div>
                <div class="card-body">
                    <!-- Flash Message -->
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Change Password Form -->
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Kata Sandi Lama</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Kata Sandi Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Perbarui Kata Sandi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
