<div class="bg-primary py-3">
    <h1 class="text-center text-white fw-bolder my-2">
        Produk Terbaru
    </h1>
    <p class="text-center text-white mb-2">
        Temukan beragam produk yang sesuai dengan gaya dan kebutuhan Anda.
    </p>
</div>
<div class="container my-3">
    <div class="row">
        @foreach ($new_products as $product)
            @php
                $variant = $product->variants->sortBy('final_price')->first();
                $discountedPrice = $variant
                    ? $variant->price - $variant->price * ($variant->discount / 100)
                    : $product->price;
            @endphp
            <div class="col-12 col-md-3 mb-2">
                <div class="card shadow-sm position-relative overflow-hidden">
                    <!-- Seluruh card akan terkena efek hover -->
                    <div class="product-image position-relative">
                        @php
                            $variant = $product->variants->sortBy('final_price')->first();
                            $maxDiscount = $variant->discount ?? 0;
                            $discountedPrice = $variant->final_price ?? $product->final_price;
                        @endphp
                        <div class="product-image">
                            @if ($maxDiscount > 0)
                                {{-- <span class="discount-tag">{{ $maxDiscount }}%</span> --}}
                            @endif

                            <span class="new-tag fw-bold"
                                style="position: absolute; top: 10px; left: 10px; background-color: #ffc107; color: #000; padding: 5px 10px; font-size: 0.75rem; z-index: 3; border-radius: 0.25rem;">
                                BARU
                            </span>

                            <!-- Love icon button -->
                            <button type="button" class="btn btn-sm btn-outline-secondary favorite-btn"
                                data-product-id="{{ $product->id }}" onclick="toggleFavorite(this)">
                                <i
                                    class="bi bi-heart{{ Auth::check() && $product->isInWishlist() ? '-fill text-danger' : '' }}"></i>
                            </button>

                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                alt="{{ $product->name }}" />
                        </div>
                    </div>

                    <!-- Overlay saat hover di seluruh card -->
                    <div class="product-overlay d-flex flex-column justify-content-center align-items-center"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(13, 110, 253, 0.9); opacity: 0; transition: opacity 0.3s ease-in-out; z-index: 2;">
                        <h5 class="text-white fw-bold mb-3">Produk Baru</h5>
                        <a href="{{ route('products.detail', $product->id) }}" class="btn btn-light">Lihat</a>
                    </div>

                    <div class="card-body">
                        <h6 class="card-title mb-2" style="text-align: left">
                            <a href="{{ route('products.detail', $product->id) }}">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <p class="card-text">
                            @php
                                $variant = $product->variants->sortBy('final_price')->first();
                                $maxDiscount = $variant->discount ?? 0;
                                $discountedPrice = $variant->final_price ?? $product->final_price;
                            @endphp

                            @if ($maxDiscount > 0)
                                <span class="fw-bolder text-danger text-decoration-line-through">
                                    Rp {{ number_format($variant->price ?? $product->price, 0, ',', '.') }}
                                </span>
                                <span class="fw-normal text-primary d-block">
                                    Rp {{ number_format($discountedPrice, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="fw-normal text-primary d-block">
                                    Rp {{ number_format($variant->price ?? $product->price, 0, ',', '.') }}
                                </span>
                            @endif

                            <small class="fw-light text-muted-flex">
                                <span class="text-warning bi bi-star-fill"></span>
                                ({{ number_format($product->average_rating, 1) }})
                                |
                                {{ $product->reviews()->approved()->count() }} Ulasan
                            </small>
                        </p>
                        <div class="d-flex justify-content-center align-items-center">
                            <a href="{{ route('products.detail', $product->id) }}" class="btn btn-sm btn-primary">
                                Lihat Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inline CSS for hover effect -->
            <style>
                .card:hover .product-overlay {
                    opacity: 1 !important;
                }
            </style>
        @endforeach
    </div>
</div>
