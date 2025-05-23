<div class="position-relative">

    <!-- Navigasi Swiper di luar container -->
    <div class="swiper-button-prev bg-secondary text-primary px-3 py-3 mx-0 rounded-4">
    </div>
    <div class="swiper-button-next bg-secondary text-primary px-3 py-3 mx-0 rounded-4">
    </div>

    <!-- Container utama -->
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <h1 class="text-white fw-bolder mx-2" data-aos="fade-up" data-aos-duration="1000">Flash Sale</h1>
                <div id="flash-sale-timer" class="text-white fw-bold me-3"></div>
            </div>
            <a href="{{ route('products.all', ['is_discount' => 1]) }}"
                class="btn btn-secondary rounded-4 {{ request('is_discount') ? 'active' : '' }}">
                <i class="bi bi-tag-fill me-1"></i> Lihat Semua
            </a>
        </div>

        <!-- Swiper -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @for ($i = 0; $i < 1; $i++)
                    @foreach ($product_flash_sale as $product)
                        <div class="swiper-slide h-100">
                            <div class="card shadow-sm h-100 d-flex flex-column">
                                <a href="{{ route('products.detail', $product->id) }}">
                                    @php
                                        $variant = $product->variants->sortBy('final_price')->first();
                                        $maxDiscount = $variant->discount ?? 0;
                                        $discountedPrice = $variant->final_price ?? $product->final_price;
                                    @endphp
                                    <div class="product-image">
                                        @if ($maxDiscount > 0)
                                            <span class="discount-tag">{{ $maxDiscount }}%</span>
                                        @endif

                                        <!-- Love icon button -->
                                        <button type="button" class="btn btn-sm btn-outline-secondary favorite-btn"
                                            data-product-id="{{ $product->id }}" onclick="toggleFavorite(this)">
                                            <i
                                                class="bi bi-heart{{ Auth::check() && $product->isInWishlist() ? '-fill text-danger' : '' }}"></i>
                                        </button>

                                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                            alt="{{ $product->name }}" />
                                    </div>
                                </a>
                                <div class="card-body d-flex flex-column flex-grow-1">
                                    <h6 class="card-title product-title mb-2">
                                        <a href="{{ route('products.detail', $product->id) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <p class="card-text d-block">
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
                                        <small class="fw-light text-muted d-block">
                                            <span class="text-warning bi bi-star-fill"></span>
                                            ({{ number_format($product->average_rating, 1) }})
                                            |
                                            {{ $product->reviews()->approved()->count() }} Ulasan
                                        </small>
                                    </p>
                                    <div class="d-flex justify-content-center mt-auto">
                                        <a href="{{ route('products.detail', $product->id) }}"
                                            class="btn btn-sm btn-primary">Lihat Selengkapnya</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endfor
            </div>
            <div class="swiper-pagination mt-3"></div>
        </div>
    </div>
</div>
