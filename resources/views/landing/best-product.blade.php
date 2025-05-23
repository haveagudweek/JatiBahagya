{{-- <div class="bg-primary py-3">
    <h1 class="text-center text-white fw-bolder my-2">
        Best Item Of The Month
    </h1>
    <p class="text-center text-white mb-2">
        Temukan beragam produk yang sesuai dengan gaya dan kebutuhan Anda.
    </p>
</div>

<div class="container my-3">
    <div class="text-center my-2">
        <button class="btn btn-primary me-1 tab-btn" data-category="best_items">10 Produk Terbaik</button>

        @foreach ($top_categories as $category)
            <button class="btn btn-primary me-1 tab-btn" data-category="category_{{ $category->id }}">
                10 {{ $category->name }} Terbaik
            </button>
        @endforeach
    </div>

    <!-- Swiper Container -->
    <div class="swiper productSwiper">
        <div class="swiper-wrapper">

            <!-- Best Items -->
            <div class="swiper-slide product-list" id="best_items">
                @foreach ($best_items as $product)
                    <div class="card shadow-sm">
                        <img src="{{ asset('storage/' . ($product->image ?? 'images/default.png')) }}"
                            class="bd-placeholder-img card-img-top" alt="{{ $product->name }}" />
                        <div class="card-body">
                            <h6 class="card-title product-title mb-2">
                                <a href="{{ route('products.detail', $product->id) }}">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-black d-flex mb-0">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</span>
                                <small class="fw-light text-muted">
                                    <span class="text-warning bi bi-star-fill"></span>
                                    4.9 | 250 Ulasan
                                </small>
                            </p>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-sm btn-primary">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Produk Berdasarkan Kategori -->
            @foreach ($top_categories as $category)
                <div class="swiper-slide product-list" id="category_{{ $category->id }}" style="display: none;">
                    @foreach ($category_products[$category->id] as $product)
                        <div class="card shadow-sm">
                            <img src="{{ asset('storage/' . ($product->image ?? 'images/default.png')) }}"
                                class="bd-placeholder-img card-img-top" alt="{{ $product->name }}" />
                            <div class="card-body">
                                <h6 class="card-title product-title mb-2">
                                    <a href="{{ route('products.detail', $product->id) }}">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <p class="card-text">
                                    <span class="fw-normal text-black d-flex mb-0">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <small class="fw-light text-muted">
                                        <span class="text-warning bi bi-star-fill"></span>
                                        4.9 | 250 Ulasan
                                    </small>
                                </p>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

        </div>
        
        <!-- Navigasi Swiper -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>

    </div>
</div> --}}

<div class="bg-primary py-3">
    <h1 class="text-center text-white fw-bolder my-2">
        Best Item Of The Month
    </h1>
    <p class="text-center text-white mb-2">
        Temukan beragam produk yang sesuai dengan gaya dan kebutuhan Anda.
    </p>
</div>

<div class="container my-3">
    <div class="text-center my-2">
        <button class="btn btn-primary me-1 tab-btn" data-category="best_items">10 Produk Terbaik</button>

        @foreach ($top_categories as $category)
            <button class="btn btn-primary me-1 tab-btn" data-category="category_{{ $category->id }}">
                10 Produk {{ $category->name }} Terbaik
            </button>
        @endforeach
    </div>

    <!-- Best Items -->
    <div class="row">
        <!-- Produk Terbaik -->
        <div class="row product-list" id="best_items">
            @foreach ($best_items as $product)
                <div class="col-12 col-md-3 mb-2">
                    <div class="card shadow-sm">
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
                        <div class="card-body">
                            <h6 class="card-title product-title mb-2">
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

                                <small class="fw-light text-muted">
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
            @endforeach
        </div>

        <!-- Produk Berdasarkan Kategori Terbanyak -->
        @foreach ($top_categories as $category)
            <div class="row product-list" id="category_{{ $category->id }}" style="display: none;">
                @foreach ($category_products[$category->id] as $product)
                    <div class="col-12 col-md-3 mb-2">
                        <div class="card shadow-sm">
                            <img src="{{ asset('storage/' . ($product->image ?? 'images/default.png')) }}"
                                class="bd-placeholder-img card-img-top" alt="{{ $product->name }}" />
                            <div class="card-body">
                                <h6 class="card-title product-title mb-2">
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

                                    <span class="fw-bolder text-danger text-decoration-line-through">
                                        Rp {{ number_format($variant->price ?? $product->price, 0, ',', '.') }}
                                    </span>
                                    <span class="fw-normal text-primary d-block">
                                        Rp {{ number_format($discountedPrice, 0, ',', '.') }}
                                    </span>
                                    <small class="fw-light text-muted">
                                        <span class="text-warning bi bi-star-fill"></span>
                                        4.9 | 250 Ulasan
                                    </small>
                                </p>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
