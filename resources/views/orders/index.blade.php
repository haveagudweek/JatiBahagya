@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                @include('components.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-md-9 mb-3">
                <h5 class="mb-2">Pesanan Saya</h5>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <!-- Filter -->
                        <form method="GET" class="d-flex gap-2">
                            <select name="status" class="form-select me-2">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Diproses
                                </option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Dibatalkan
                                </option>
                            </select>
                            <select name="payment_status" class="form-select me-2">
                                <option value="">Semua Pembayaran</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Belum
                                    Dibayar</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah
                                    Dibayar</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>

                    <div class="card-body">
                        @if ($orders->isEmpty())
                            <p class="text-center">Tidak ada pesanan.</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="d-none">#</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="d-none">{{ $loop->iteration }}</td>
                                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                            <td>Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $order->status == 'completed'
                                                        ? 'success'
                                                        : ($order->status == 'canceled'
                                                            ? 'danger'
                                                            : ($order->status == 'shipped'
                                                                ? 'primary'
                                                                : ($order->status == 'process'
                                                                    ? 'warning'
                                                                    : 'secondary'))) }}">
                                                    {{ $order->status == 'completed'
                                                        ? 'Selesai'
                                                        : ($order->status == 'canceled'
                                                            ? 'Dibatalkan'
                                                            : ($order->status == 'shipped'
                                                                ? 'Dikirim'
                                                                : ($order->status == 'process'
                                                                    ? 'Dalam Proses'
                                                                    : 'Menunggu Pembayaran'))) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'danger' }}">
                                                    {{ $order->payment_status == 'paid' ? 'Lunas' : 'Belum Dibayar' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.detail', $order->order_code) }}"
                                                    class="btn btn-sm btn-info">Detail</a>
                                                @if ($order->status == 'pending')
                                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger">Batalkan</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
