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
                <h5 class="mb-3"><i class="fas fa-shopping-bag"></i> Detail Pesanan</h5>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong>Nomor Pesanan: #{{ $order->order_code }}</strong>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">

                        @if ($order->status == 'pending' && $order->payment_status == 'unpaid')
                            <div class="alert alert-info" role="alert">
                                <h6 class="alert-heading">Menunggu Pembayaran</h6>
                                <p>Silakan lakukan pembayaran transfer ke rekening berikut:</p>
                                <ul class="list-unstyled">
                                    <li><strong>Bank:</strong> Bank Central Asia</li>
                                    <li><strong>Nomor Rekening:</strong> 67199219228</li>
                                    <li><strong>Atas Nama:</strong> PT WaveMoon Indonesia Abadi</li>
                                    <li><strong>Jumlah:</strong> Rp {{ number_format($order->amount, 0, ',', '.') }}</li>
                                </ul>
                                <p>Setelah melakukan pembayaran, mohon konfirmasi pembayaran Anda agar pesanan dapat segera
                                    diproses.</p>
                                <a href="" class="btn btn-primary">Konfirmasi
                                    Pembayaran</a>
                            </div>
                        @endif

                        <!-- Status Pesanan -->
                        <h5 class="mt-4"><i class="fas fa-receipt"></i> Status Pesanan</h5>
                        <div class="mb-2 bg-light rounded">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th class="text-start">Tanggal</th>
                                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Status Pesanan</th>
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
                                                        <i class="fas fa-circle"></i>
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
                                            </tr>
                                            <tr>
                                                <th class="text-start">Status Pembayaran</th>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'refunded' ? 'secondary' : 'danger') }}">
                                                        <i class="fas fa-wallet"></i>
                                                        {{ $order->payment_status == 'paid' ? 'Lunas' : ($order->payment_status == 'refunded' ? 'Dikembalikan' : 'Belum Dibayar') }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Total Pesanan</th>
                                                <td>Rp {{ number_format($order->total_order, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Total Pengiriman</th>
                                                <td>Rp {{ number_format($order->total_shipping, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Total Biaya</th>
                                                <td>Rp {{ number_format($order->total_fee, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Total Pembayaran</th>
                                                <td>
                                                    <b class="text-primary fw-bold">Rp
                                                        {{ number_format($order->amount, 0, ',', '.') }}
                                                    </b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <!-- Timeline Status Pesanan -->
                        <div class="mt-4">
                            <h6 class="fw-bold">Progres Pesanan</h6>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar 
                                @if ($order->status == 'pending') bg-warning 
                                @elseif($order->status == 'process') bg-primary
                                @elseif($order->status == 'shipped') bg-info
                                @else bg-success @endif"
                                    role="progressbar"
                                    style="width: 
                                @if ($order->status == 'pending') 25%
                                @elseif($order->status == 'process') 50%
                                @elseif($order->status == 'shipped') 75%
                                @else 100% @endif">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small>Pending</small>
                                <small>Diproses</small>
                                <small>Dikirim</small>
                                <small>Selesai</small>
                            </div>
                        </div>

                        <!-- Produk dalam Pesanan -->
                        <h5 class="mt-4"><i class="fas fa-box"></i> Produk dalam Pesanan</h5>
                        <div class="row">
                            <div class="col-md-12">
                                @foreach ($order->orderItems as $item)
                                    <div class="card shadow-sm border-0 mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-3">
                                                @if ($item->variant && $item->variant->image)
                                                    <img src="{{ asset('storage/' . $item->variant->image) }}"
                                                        class="img-fluid rounded-start" alt="{{ $item->product->name }}">
                                                @else
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                        class="img-fluid rounded-start" alt="{{ $item->product->name }}">
                                                @endif
                                            </div>
                                            <div class="col-md-9">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $item->product->name }}</h6>

                                                    @if ($item->variant)
                                                        <div class="text-muted small mb-2">
                                                            {{ $item->variant->name }}
                                                            @foreach ($item->variant->attributeValues as $attribute)
                                                                <strong>{{ $attribute->attribute->name }}:</strong>
                                                                {{ $attribute->value }}
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    <p class="card-text">
                                                        <strong>Jumlah:</strong> {{ $item->quantity }} <br>
                                                        <strong>Harga:</strong> Rp
                                                        {{ number_format($item->price_per_item, 0, ',', '.') }} <br>
                                                        <strong>Subtotal:</strong> Rp
                                                        {{ number_format($item->total_price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Informasi Pengiriman -->
                        <h5 class="mt-4"><i class="fas fa-truck"></i> Informasi Pengiriman</h5>
                        <div class="mb-2">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th width="30%" class="text-start">Nama Penerima</th>
                                        <td>{{ $order->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">Alamat</th>
                                        <td>
                                            {{ ucwords(
                                                strtolower(
                                                    sprintf(
                                                        '%s, %s, %s, %s, %s, %d',
                                                        ucwords($order->userAddress->full_address),
                                                        $order->userAddress->village->name,
                                                        $order->userAddress->district->name,
                                                        $order->userAddress->regency->name,
                                                        $order->userAddress->province->name,
                                                        $order->userAddress->postal_code,
                                                    ),
                                                ),
                                            ) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">Kurir</th>
                                        <td>{{ strtoupper($order->shippings->courier_name ?? '-') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">No. Resi</th>
                                        <td>
                                            @if ($order->shippings && $order->shippings->tracking_number)
                                                <span class="badge bg-info py-2 px-2">{{ $order->shippings->tracking_number }}</span>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#shippingDetailsModal">
                                                    <i class="fas fa-search"></i> Lacak Resi
                                                </button>
                                            @else
                                                <span class="text-muted">Belum tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Modal RESI --}}
                        <div class="modal fade" id="shippingDetailsModal" tabindex="-1"
                            aria-labelledby="shippingDetailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="shippingDetailsModalLabel">Detail Pengiriman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($order->shippings)
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">No. Resi:</th>
                                                        <td>{{ $order->shippings->tracking_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Status:</th>
                                                        <td>
                                                            @php
                                                                $status = strtolower($order->shippings->status); // Convert to lowercase for consistent display
                                                                $formattedStatus = ucwords(
                                                                    str_replace('_', ' ', $status),
                                                                ); // Format status (e.g., in_transit -> In Transit)
                                                            @endphp
                                                            {{ $formattedStatus }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Kurir:</th>
                                                        <td>{{ strtoupper($order->shippings->courier_name) }}</td>
                                                    </tr>
                                                    @if ($order->shippings->estimated_delivery_date)
                                                        <tr>
                                                            <th scope="row">Estimasi Tanggal Kirim:</th>
                                                            <td>{{ \Carbon\Carbon::parse($order->shippings->estimated_delivery_date)->format('Y-m-d') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($order->shippings->delivered_at)
                                                        <tr>
                                                            <th scope="row">Tanggal Sampai:</th>
                                                            <td>{{ \Carbon\Carbon::parse($order->shippings->delivered_at)->format('Y-m-d') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        @else
                                            <p>Data pengiriman tidak tersedia.</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-4">
                            @if ($order->status == 'pending')
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i> Batalkan Pesanan
                                    </button>
                                </form>
                            @endif

                            @if ($order->status == 'shipped' && $order->tracking_number)
                                <a href="https://cekresi.com/?noresi={{ $order->tracking_number }}" target="_blank"
                                    class="btn btn-success">
                                    <i class="fas fa-map-marker-alt"></i> Lihat Resi
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
