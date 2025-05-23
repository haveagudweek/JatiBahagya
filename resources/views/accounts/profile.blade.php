@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <!-- Sidebar -->
        <div class="col-md-3 mb-3">
            @include('components.sidebar')
        </div>

        <!-- Profile Section -->
        <div class="col-md-9 mb-3">
            <h5 class="mb-2">Pengaturan Profil</h5>
            <div class="card">
                <div class="card-header">Profil</div>
                <div class="card-body">
                    <!-- Flash Message -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Profile Form -->
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
