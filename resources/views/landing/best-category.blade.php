<h1 class="text-center fw-bolder mb-2">Kategori Pilihan</h1>
<p class="text-center mb-5">
    Temukan beragam produk yang sesuai dengan gaya dan kebutuhan Anda.
</p>

<div class="swiper categorySwiper">
    <div class="swiper-wrapper">
        @foreach ($preview_categories as $category)
            <div class="swiper-slide">
                <div class="card position-relative">
                    <img src="{{ $category->image_url ?? asset('images/default-category.png') }}" class="card-img"
                        alt="{{ $category->name }}" />
                    <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center p-3"
                        style="background-color: rgba(0, 0, 0, 0.5)">
                        <h5 class="card-title text-white text-center mt-auto mb-auto fw-bold">
                            {{ strtoupper($category->name) }}</h5>
                        <a href="{{ route('products.all', ['category' => $category->id]) }}"
                            class="btn btn-sm btn-primary rounded-3">Lihat Selengkapnya</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>
