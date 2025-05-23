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
                <div class="card">
                    <div class="card-header">Tambah Alamat</div>
                    <div class="card-body">
                        <!-- Flash Message -->
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('address.store') }}" method="POST">
                            @csrf

                            <!-- Provinsi -->
                            <div class="mb-3">
                                <label for="province" class="form-label">Provinsi</label>
                                <select name="province_id" id="province" class="form-control" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Kabupaten/Kota -->
                            <div class="mb-3">
                                <label for="regency" class="form-label">Kabupaten/Kota</label>
                                <select name="regency_id" id="regency" class="form-control" required>
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                            </div>

                            <!-- Kecamatan -->
                            <div class="mb-3">
                                <label for="district" class="form-label">Kecamatan</label>
                                <select name="district_id" id="district" class="form-control" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            <!-- Desa/Kelurahan -->
                            <div class="mb-3">
                                <label for="village" class="form-label">Desa/Kelurahan</label>
                                <select name="village_id" id="village" class="form-control" required>
                                    <option value="">Pilih Desa/Kelurahan</option>
                                </select>
                            </div>

                            <!-- Alamat Lengkap -->
                            <div class="mb-3">
                                <label for="full_address" class="form-label">Alamat Lengkap</label>
                                <input type="text" name="full_address" class="form-control" required>
                            </div>

                            <!-- Kode Pos -->
                            <div class="mb-3">
                                <label for="postal_code" class="form-label">Kode Pos</label>
                                <input type="text" name="postal_code" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Menangani perubahan Provinsi
            $('#province').on('change', function() {
                var province_id = $(this).val();
                if (province_id) {
                    $.ajax({
                        url: '/api/regencies/' + province_id,
                        type: 'GET',
                        success: function(data) {
                            $('#regency').empty();
                            $('#regency').append(
                                '<option value="">Pilih Kabupaten/Kota</option>');
                            data.forEach(function(regency) {
                                $('#regency').append('<option value="' + regency.id +
                                    '">' + regency.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#regency').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                    $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                    $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                }
            });

            // Menangani perubahan Kabupaten/Kota
            $('#regency').on('change', function() {
                var regency_id = $(this).val();
                if (regency_id) {
                    $.ajax({
                        url: '/api/districts/' + regency_id,
                        type: 'GET',
                        success: function(data) {
                            $('#district').empty();
                            $('#district').append('<option value="">Pilih Kecamatan</option>');
                            data.forEach(function(district) {
                                $('#district').append('<option value="' + district.id +
                                    '">' + district.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                    $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                }
            });

            // Menangani perubahan Kecamatan
            $('#district').on('change', function() {
                var district_id = $(this).val();
                if (district_id) {
                    $.ajax({
                        url: '/api/villages/' + district_id,
                        type: 'GET',
                        success: function(data) {
                            $('#village').empty();
                            $('#village').append(
                                '<option value="">Pilih Desa/Kelurahan</option>');
                            data.forEach(function(village) {
                                $('#village').append('<option value="' + village.id +
                                    '">' + village.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                }
            });
        });
    </script>
@endsection
