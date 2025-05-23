@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Keranjang Belanja</h4>

        @if ($cartItems->isEmpty())
            {{-- Alert jika keranjang kosong --}}
            <div class="d-flex flex-column align-items-center justify-content-center py-5">
                {{-- <img src="{{ asset('images/empty-cart.png') }}" alt="Keranjang Kosong" class="mb-3" style="max-width: 150px;"> --}}
                <h5 class="mb-2 fw-bold text-muted">Oops! Keranjang kamu masih kosong.</h5>
                <p class="text-muted">Yuk, lihat koleksi produk kami dan temukan yang kamu suka! 😊</p>
                <a href="{{ route('products.all') }}" class="btn btn-primary shadow-sm px-4 py-2">
                    <i class="bi bi-cart-plus"></i> Lihat Produk
                </a>
            </div>
        @else
            {{-- Menampilkan Keranjang Belanja --}}
            <div class="row">
                @foreach ($cartItems as $cartItem)
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div class="row d-flex align-items-center">
                                    {{-- Gambar Produk --}}
                                    <div class="col-6 col-md-3 px-2 py-2">
                                        @if ($cartItem->variant && $cartItem->variant->image)
                                            <img src="{{ asset('storage/' . $cartItem->variant->image) }}"
                                                alt="{{ $cartItem->product->name }}" class="img-fluid rounded-4"
                                                style="height: 100px">
                                        @else
                                            <img src="{{ asset('storage/' . $cartItem->product->image) }}"
                                                alt="{{ $cartItem->product->name }}" class="img-fluid rounded-4"
                                                style="height: 100px">
                                        @endif
                                    </div>

                                    {{-- Deskripsi Produk --}}
                                    <div class="col-6 col-md-5">
                                        <h5 class="card-title">{{ $cartItem->product->name }}</h5>

                                        {{-- Tampilkan informasi varian jika ada --}}
                                        @if ($cartItem->variant)
                                            <p class="card-text text-muted mb-1">
                                                @foreach ($cartItem->variant->attributeValues as $attribute)
                                                    {{ $attribute->attribute->name }}: {{ $attribute->value }}@if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </p>
                                        @endif

                                        <p class="card-text text-muted">
                                            Rp
                                            {{ number_format($cartItem->price ?? $cartItem->product->price, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    {{-- Jumlah dan Total --}}
                                    <div class="col-6 col-md-2">
                                        <div class="d-flex align-items-center mb-2">
                                            <button class="btn btn-sm btn-outline-secondary update-quantity"
                                                data-id="{{ $cartItem->id }}" data-type="decrease">-</button>
                                            <input type="text" value="{{ $cartItem->quantity }}"
                                                class="form-control form-control-sm text-center quantity-input"
                                                data-id="{{ $cartItem->id }}" style="width: 50px;">
                                            <button class="btn btn-sm btn-outline-secondary update-quantity"
                                                data-id="{{ $cartItem->id }}" data-type="increase">+</button>
                                        </div>
                                        <p class="font-weight-bold mb-0">
                                            Total: Rp
                                            {{ number_format($cartItem->quantity * ($cartItem->price ?? $cartItem->product->price), 0, ',', '.') }}
                                        </p>
                                    </div>

                                    {{-- Aksi --}}
                                    <div class="col-6 col-md-2 mt-2 text-right">
                                        <button class="btn btn-sm btn-danger remove-item"
                                            data-id="{{ $cartItem->id }}">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Total dan Checkout --}}
            <div class="row mt-4">
                <div class="col-12 col-md-6">
                    <h4 class="fw-bolder">Total:
                        <span class="text-danger">Rp
                            {{ number_format(
                                $cartItems->sum(function ($item) {
                                    $price = $item->price ?? $item->product->price;
                                    return $item->quantity * $price;
                                }),
                                0,
                                ',',
                                '.',
                            ) }}
                        </span>
                    </h4>
                </div>
                <div class="col-12 col-md-6 text-end">
                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary">Checkout</a>
                </div>
            </div>
        @endif

        {{-- Toast Container --}}
        <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Fungsi untuk menampilkan notifikasi Bootstrap Toast
            function showToast(message, type = 'success') {
                const toast = `
                <div class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
                `;
                $("#toast-container").append(toast);
                const toastElement = new bootstrap.Toast($("#toast-container .toast").last()[0]);
                toastElement.show();
            }

            // Update Quantity
            $(".update-quantity").click(function() {
                let cartId = $(this).data("id");
                let type = $(this).data("type");
                let inputField = $(`.quantity-input[data-id="${cartId}"]`);
                let currentQuantity = parseInt(inputField.val());

                if (type === "increase") {
                    currentQuantity++;
                } else if (type === "decrease" && currentQuantity > 1) {
                    currentQuantity--;
                }

                $.ajax({
                    url: `/cart/update/${cartId}`,
                    type: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        quantity: currentQuantity
                    },
                    success: function(response) {
                        inputField.val(currentQuantity);
                        showToast("Jumlah produk diperbarui!", "success");
                        location.reload(); // Reload untuk update total harga
                    },
                    error: function() {
                        showToast("Terjadi kesalahan!", "danger");
                    }
                });
            });

            // Delete Item
            $(".remove-item").click(function() {
                let cartId = $(this).data("id");

                $.ajax({
                    url: `/cart/remove/${cartId}`,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        showToast("Produk dihapus dari keranjang!", "success");
                        location.reload(); // Reload halaman untuk update tampilan
                    },
                    error: function() {
                        showToast("Terjadi kesalahan!", "danger");
                    }
                });
            });
        });
    </script>
@endsection
