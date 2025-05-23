<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active" style="background-image: url('{{ asset('images/carousell-1.png') }}')">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="text-white">STUDENT ROOM</h1>
                <a href="{{ route('products.all') }}" class="btn btn-primary mt-3">Selengkapnya</a>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="carousel-item" style="background-image: url('{{ asset('images/carousell-2.png') }}')">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="text-white">KITCHEN ROOM</h1>
                <a href="{{ route('products.all') }}" class="btn btn-primary mt-3">Selengkapnya</a>
            </div>
        </div>
    </div>
    <!-- Tombol Navigasi Carousel -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-primary rounded-4" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-primary rounded-4" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
