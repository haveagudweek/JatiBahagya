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
                <h5 class="mb-2">Alamat Pengguna</h5>
                
                <!-- Flash Message -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Address List Section -->
                <div class="mb-2">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Alamat Lengkap</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (auth()->user()->addresses->isEmpty())
                                <tr>
                                    <td colspan="2" class="text-center text-muted">
                                        Kamu belum memiliki alamat. Silakan tambahkan alamat terlebih dahulu.
                                    </td>
                                </tr>
                            @else
                                @foreach (auth()->user()->addresses as $address)
                                    <tr>
                                        <td>
                                            {{ ucwords(
                                                strtolower(
                                                    sprintf(
                                                        '%s, %s, %s, %s, %s, %d',
                                                        ucwords($address->full_address),
                                                        $address->village->name,
                                                        $address->district->name,
                                                        $address->regency->name,
                                                        $address->province->name,
                                                        $address->postal_code,
                                                    ),
                                                ),
                                            ) }}
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('address.edit', $address->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                                <form action="{{ route('address.destroy', $address->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Apakah kamu yakin ingin menghapus alamat ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>                                        
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <a href="{{ route('address.create') }}" class="btn btn-primary w-100">Tambah Alamat</a>
                </div>
            </div>
        </div>
    </div>
@endsection
