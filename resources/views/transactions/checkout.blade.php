@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Checkout</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cart.checkout.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    {{-- Informasi Pengiriman --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Alamat Pengiriman</h5>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    value="{{ old('name', auth()->user()->name) }}">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" required
                                    value="{{ old('phone', auth()->user()->phone) }}">
                            </div>

                            @foreach (auth()->user()->addresses as $address)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="address_id"
                                        id="address{{ $address->id }}" value="{{ $address->id }}">
                                    <label class="form-check-label" for="address{{ $address->id }}">
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
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Metode Pengiriman --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Metode Pengiriman</h5>

                            @php
                                $shippingMethods = [
                                    ['method' => 'pickup', 'label' => 'Pickup di Toko', 'cost' => 0],
                                    ['method' => 'private', 'label' => 'Kurir JatiBahagya', 'cost' => 70000],
                                ];
                            @endphp

                            <div class="row">
                                @foreach ($shippingMethods as $data)
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input shipping-method" type="radio"
                                                name="shipping_method" id="{{ $data['method'] }}"
                                                value="{{ $data['method'] }}" data-cost="{{ $data['cost'] }}">
                                            <label class="form-check-label" for="{{ $data['method'] }}">
                                                {{ $data['label'] }} (Rp {{ number_format($data['cost'], 0, ',', '.') }})
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Metode Pembayaran</h5>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                    value="cod" checked>
                                <label class="form-check-label" for="cod">Bayar di Tempat (COD)</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="transfer"
                                    value="transfer">
                                <label class="form-check-label" for="transfer">Transfer Bank Virtual Account (VA)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    {{-- Ringkasan Pembelian --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Ringkasan Pembelian</h5>
                            <ul class="list-group mb-3">
                                @foreach ($cartItems as $cartItem)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span>{{ $cartItem->product->name }}</span>
                                            @if ($cartItem->variant)
                                                <div class="text-muted small">
                                                    @foreach ($cartItem->variant->attributeValues as $attribute)
                                                        {{ $attribute->attribute->name }}: {{ $attribute->value }}
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            <span class="text-muted">({{ $cartItem->quantity }})</span>
                                        </div>
                                        <span class="text-nowrap">Rp
                                            {{ number_format($cartItem->quantity * ($cartItem->price ?? $cartItem->product->price), 0, ',', '.') }}
                                        </span>
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Ongkos Kirim</span>
                                    <span id="shipping-cost">Rp
                                        {{ number_format($shippingMethods[0]['cost'], 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Biaya Layanan</span>
                                    <span>Rp 2.000</span>
                                </li>
                            </ul>

                            <h4 class="fw-bold text-end">
                                Total: <span class="text-danger" id="total-price">Rp
                                    {{ number_format($cartItems->sum(fn($item) => $item->quantity * $item->product->price) + $shippingMethods[0]['cost'] + 2000, 0, ',', '.') }}</span>
                            </h4>
                        </div>
                    </div>

                    {{-- Tombol Checkout --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary w-100">Proses Pesanan</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function untuk update total harga ketika metode pengiriman berubah
            function updateTotal() {
                let shippingCost = parseInt($("input[name='shipping_method']:checked").data("cost")) ||
                    0;
                let subtotal =
                    {{ $cartItems->sum(function ($item) {
                        return $item->quantity * ($item->price ?? $item->product->price);
                    }) }};
                let serviceFee = 2000;
                let total = subtotal + shippingCost + serviceFee;

                // Update ongkos kirim
                $("#shipping-cost").text("Rp " + shippingCost.toLocaleString("id-ID"));
                // Update total harga
                $("#total-price").text("Rp " + total.toLocaleString("id-ID"));
            }

            // Event listener untuk perubahan metode pengiriman
            $(".shipping-method").change(function() {
                updateTotal();
            });

            // Panggil fungsi pertama kali saat halaman dimuat
            updateTotal();
        });
    </script>
@endsection
