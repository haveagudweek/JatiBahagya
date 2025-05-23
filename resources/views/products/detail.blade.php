@extends('layouts.app')

@section('styles')
    <style>
        /* Di file CSS Anda */
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rating-input input[type="radio"] {
            display: none;
        }

        .rating-input label {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            margin-right: 5px;
        }

        .rating-input input[type="radio"]:checked~label,
        .rating-input input[type="radio"]:hover~label {
            color: #ffc107;
        }

        .rating-input label:hover,
        .rating-input label:hover~label {
            color: #ffc107;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">

        {{-- Tombol Back --}}
        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>

        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded-4 mb-4" alt="{{ $product->name }}">
                <img class="img-fluid w-80 rounded-2 mb-3" height="80%" src="{{ asset('images/frame-26.png') }}" alt="Promosi" />
                <img class="img-fluid w-80 rounded-2 mb-3" height="80%" src="{{ asset('images/frame-25.png') }}" alt="Promosi" />
                <img class="img-fluid w-80 rounded-2 mb-3" height="80%" src="{{ asset('images/frame-24.png') }}" alt="Promosi" />
            </div>
            <div class="col-md-6">

                <div id="PD-Head">
                    @if ($product->category)
                        <p class="fw-bold text-danger p-0 my-0">
                            {{ $product->category->name }}
                        </p>
                    @endif
                    <h3 class="py-0 my-2">{{ $product->name }}</h3>
                    <div class="d-flex align-items-center py-0 my-0">
                        <div class="text-primary">
                            <div class="star-rating">
                                @php
                                    $rating = $product->average_rating;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = $rating - $fullStars >= 0.5;
                                @endphp

                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $fullStars)
                                        <span class="bi bi-star-fill"></span>
                                    @elseif($i == $fullStars + 1 && $hasHalfStar)
                                        <span class="bi bi-star-half"></span>
                                    @else
                                        <span class="bi bi-star"></span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <small class="ms-2 fw-bolder text-primary">
                            ({{ number_format($product->average_rating, 1) }} dari 5,
                            {{ $product->reviews()->approved()->count() }} Ulasan)
                        </small>
                    </div>
                    <hr />
                </div>

                <div id="PD-Price">
                    @php
                        $hasVariants = $variants->isNotEmpty();
                    @endphp

                    @if ($hasVariants)
                        @php
                            $finalPrices = $variants->map(fn($v) => $v->final_price);
                            $originalPrices = $variants->pluck('price');
                            $maxDiscount = $variants->max('discount');
                            $minPrice = $finalPrices->min();
                            $maxPrice = $finalPrices->max();
                            $hasAnyDiscount = $variants->contains(fn($v) => $v->discount > 0);
                        @endphp

                        @if ($hasAnyDiscount)
                            <h4 class="text-muted text-decoration-line-through mb-1">
                                Rp {{ number_format($originalPrices->max(), 0, ',', '.') }}
                            </h4>
                        @endif

                        <h4 class="text-primary">
                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                            @if ($minPrice !== $maxPrice)
                                - Rp {{ number_format($maxPrice, 0, ',', '.') }}
                            @endif

                            @if ($hasAnyDiscount && $maxDiscount > 0)
                                <small class="text-danger fw-bold">-{{ $maxDiscount }}%</small>
                            @endif
                        </h4>
                    @else
                        @if ($product->discount > 0)
                            <h4 class="text-muted text-decoration-line-through mb-1">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </h4>
                            <h4 class="text-primary">
                                Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                <small class="text-danger fw-bold">-{{ $product->discount }}%</small>
                            </h4>
                        @else
                            <h4 class="text-primary">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </h4>
                        @endif
                    @endif
                </div>

                <div id="PD-Shipping">
                    <div class="table-responsive mt-4">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="p-0 m-0">
                                    <td class="p-0 m-0 text-nowrap">Pengiriman Reguler</td>
                                    <td class="p-0 m-0">
                                        <ul>
                                            <li>Rp 70.000 (Gratis ongkir untuk pembelian diatas Rp 2.000.000)</li>
                                            <li>Dikirim oleh <span class="fw-bold">JatiBahagya</span> dengan estimasi waktu
                                                pengiriman rata-rata 3 hari.</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr class="p-0 m-0">
                                    <td class="p-0 m-0 text-nowrap">Pengambilan di Toko</td>
                                    <td class="p-0 m-0">
                                        <ul>
                                            <li>
                                                Gratis tanpa biaya pengiriman.
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="PD-Variant" class="row g-2">
                    <div class="row mb-3" id="variant-selection">
                        @foreach ($product->attributes as $attribute)
                            <div class="col-md-6 mb-2">
                                <label for="attribute-{{ $attribute->id }}" class="form-label">
                                    Pilih {{ $attribute->name }}
                                </label>
                                <select id="attribute-{{ $attribute->id }}" class="form-select variant-selector"
                                    data-attribute-id="{{ $attribute->id }}">
                                    <option selected disabled>-- Pilih {{ $attribute->name }} --</option>
                                    @foreach ($attribute->values as $value)
                                        <option value="{{ $value->id }}">
                                            {{ $value->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                    <div id="selected-variant-info" class="d-none">
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title">Varian Dipilih</h5>
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <img id="variant-image" src="" class="img-fluid" style="max-height: 150px;">
                                    </div>
                                    <div class="col-md-8">
                                        <p class="mb-1"><strong>SKU:</strong> <span id="variant-sku"></span></p>
                                        <p class="mb-1"><strong>Harga:</strong> Rp <span id="variant-price"></span></p>
                                        <p class="mb-1"><strong>Stok:</strong> <span id="variant-stock"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="variant-error" class="alert alert-danger mt-3 d-none"></div>
                    <div class="mt-3 d-flex gap-2">
                        <button id="addToCartBtn" class="btn btn-primary w-50 py-2">Tambah ke Keranjang</button>
                        <button id="buyNowBtn" class="btn btn-outline-primary w-50 py-2">Beli Sekarang</button>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted d-block my-1">100% Guaranteed and Durable Products</small>
                        <div class="d-flex align-items-start my-1">
                            <i class="bi bi-gift text-primary me-2 mt-1"></i>
                            <small class="fw-bolder">Dapatkan bonus hingga 5.000 poin! Cek kehadiran setiap hari di bulan
                                Mei dan kumpulkan poin sebanyak-banyaknya!</small>
                        </div>
                        <div class="d-flex align-items-start my-1">
                            <i class="bi bi-cash-coin text-primary me-2 mt-1"></i>
                            <small class="fw-bolder">Belanja pertama kali? Nikmati cashback 3% tanpa batas!</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div id="PD-Info">
                    <div class="border-bottom">
                        <nav>
                            <div class="d-flex justify-content-center">
                                <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-detail-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-detail" type="button" role="tab"
                                        aria-controls="nav-detail" aria-selected="true">Detail Produk</button>
                                    <button class="nav-link" id="nav-review-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-review" type="button" role="tab"
                                        aria-controls="nav-review" aria-selected="false">
                                        Ulasan (<span
                                            class="review-count">{{ $product->reviews()->approved()->count() }}</span>)
                                    </button>
                                    <button class="nav-link" id="nav-question-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-question" type="button" role="tab"
                                        aria-controls="nav-question" aria-selected="false">Tanya Jawab
                                        ({{ $product->questions->where('is_visible', true)->count() }})</button>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-detail" role="tabpanel"
                            aria-labelledby="nav-detail-tab" tabindex="0">
                            <div class="mt-2">
                                <p>
                                    {!! $product->description !!}</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab"
                            tabindex="0">
                            <!-- Form Review -->
                            <!-- Modal -->
                            <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reviewModalLabel">Beri Ulasan Produk</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="reviewForm" action="{{ route('reviews.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                <!-- Rating -->
                                                <div class="mb-4">
                                                    <label class="form-label fs-5">Bagaimana penilaian Anda?</label>
                                                    <div class="rating-input text-center">
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            <input type="radio" id="modalStar{{ $i }}"
                                                                name="rating" value="{{ $i }}" required>
                                                            <label for="modalStar{{ $i }}"
                                                                class="bi bi-star-fill fs-1"></label>
                                                        @endfor
                                                    </div>
                                                    @error('rating')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Judul -->
                                                <div class="mb-3">
                                                    <label for="modalReviewTitle" class="form-label">Judul Ulasan</label>
                                                    <input type="text"
                                                        class="form-control @error('title') is-invalid @enderror"
                                                        id="modalReviewTitle" name="title" required maxlength="100"
                                                        placeholder="Contoh: Sangat memuaskan">
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Komentar -->
                                                <div class="mb-3">
                                                    <label for="modalReviewComment" class="form-label">Ulasan Anda</label>
                                                    <textarea class="form-control @error('comment') is-invalid @enderror" id="modalReviewComment" name="comment"
                                                        rows="5" required placeholder="Bagikan pengalaman Anda menggunakan produk ini"></textarea>
                                                    @error('comment')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">
                                                        <span class="spinner-border spinner-border-sm d-none"
                                                            role="status"></span>
                                                        Kirim Ulasan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary my-4" data-bs-toggle="modal"
                                data-bs-target="#reviewModal">
                                <i class="bi bi-pencil-square"></i> Tulis Ulasan
                            </button>

                            <!-- Daftar Review -->
                            <div class="reviews-list">
                                @foreach ($product->reviews->where('is_approved', true) as $review)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <img src="{{ $review->user->avatar ?? 'https://www.gravatar.com/avatar/' . md5($review->user->email) }}"
                                                    alt="user-avatar" class="rounded-circle me-3" width="60"
                                                    height="60">

                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <!-- Rating -->
                                                        <div class="text-warning">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $review->rating)
                                                                    <i class="bi bi-star-fill"></i>
                                                                @else
                                                                    <i class="bi bi-star"></i>
                                                                @endif
                                                            @endfor
                                                        </div>

                                                        <!-- Info Reviewer -->
                                                        <small class="text-muted">
                                                            Oleh {{ $review->user->name }} —
                                                            {{ $review->created_at->format('d M Y') }}
                                                        </small>
                                                    </div>

                                                    <h6 class="my-2">{{ $review->title }}</h6>
                                                    <p class="mb-0">{{ $review->comment }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-question" role="tabpanel" aria-labelledby="nav-question-tab"
                            tabindex="0">
                            <div class="mt-2">
                                <!-- Form Pertanyaan Baru -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Ajukan Pertanyaan</h5>
                                        <form id="questionForm"
                                            action="{{ route('product.questions.store', $product->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <textarea class="form-control" name="question" rows="3"
                                                    placeholder="Tulis pertanyaan Anda tentang produk ini..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Kirim Pertanyaan</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Daftar Pertanyaan -->
                                <div class="mb-4">
                                    <h5>Pertanyaan dan Jawaban</h5>

                                    @if ($product->questions->where('is_visible', true)->count() > 0)
                                        @foreach ($product->questions->where('is_visible', true) as $question)
                                            <div class="mb-4">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="text-muted">
                                                            {{ substr($question->user->name, 0, 1) }}***
                                                            {{ substr($question->user->name, -1) }}***
                                                        </span>
                                                        @if ($question->answer)
                                                            <span class="badge bg-dark ms-2">Sudah Dijawab</span>
                                                        @else
                                                            <span class="badge bg-secondary ms-2">Belum Dijawab</span>
                                                        @endif
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $question->created_at->format('d M Y') }}</small>
                                                </div>

                                                <hr class="my-2">

                                                <p class="fw-bolder mb-1">Q: {{ $question->question }}</p>
                                                @if (auth()->id() == $question->user_id)
                                                    <small class="text-muted d-block mb-2">(Pertanyaan Anda)</small>
                                                @endif

                                                @if ($question->answer)
                                                    <div class="bg-light p-3 rounded mt-2">
                                                        <p class="fw-bolder mb-1">A: {{ $question->answer }}</p>
                                                        <small class="text-muted">Dijawab oleh
                                                            {{ $question->answerer->name ?? 'Admin' }}
                                                            pada {{ $question->answered_at->format('d M Y') }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-info">
                                            Belum ada pertanyaan untuk produk ini.
                                        </div>
                                    @endif
                                </div>

                                <!-- Informasi -->
                                <div class="alert alert-warning">
                                    <ul class="mb-0">
                                        <li>Untuk pertanyaan mengenai pengiriman/pembayaran, silakan hubungi Pusat Pelanggan
                                        </li>
                                        <li>Konten tidak pantas akan dihapus dan akun bisa dibatasi</li>
                                        <li>Informasi pribadi akan dihapus untuk perlindungan privasi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toast Section --}}
        <div class="mt-5">
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <strong class="me-auto text-white">Sukses</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Produk berhasil ditambahkan ke keranjang!
                    </div>
                </div>
            </div>
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
                {{-- SESSION SUCCESS --}}
                @if (session('status'))
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-success text-white">
                            <strong class="me-auto">Sukses</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                {{-- SESSION ERROR --}}
                @if (session('error'))
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-danger text-white">
                            <strong class="me-auto">Gagal</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                {{-- VALIDATION ERRORS --}}
                @if ($errors->any())
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-warning text-dark">
                            <strong class="me-auto">Validasi</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const variantData = @json($variantData);
            const selectElements = document.querySelectorAll('.variant-selector');
            const selectedAttributes = {};
            const addToCartBtn = document.getElementById('addToCartBtn');
            const buyNowBtn = document.getElementById('buyNowBtn');
            const errorDiv = document.getElementById('variant-error');

            // Inisialisasi status awal
            disableButtons();

            // Inisialisasi objek untuk menyimpan pilihan
            variantData.attributes.forEach(attr => {
                selectedAttributes[attr.id] = null;
            });

            // Fungsi untuk enable/disable buttons
            function updateButtons(variant) {
                if (variant && variant.stock > 0) {
                    enableButtons();
                } else {
                    disableButtons();
                }
            }

            function enableButtons() {
                addToCartBtn.disabled = false;
                buyNowBtn.disabled = false;
                addToCartBtn.classList.remove('btn-secondary');
                buyNowBtn.classList.remove('btn-secondary');
                addToCartBtn.classList.add('btn-primary');
                buyNowBtn.classList.add('btn-outline-primary');
                errorDiv.classList.add('d-none');
            }

            function disableButtons() {
                addToCartBtn.disabled = true;
                buyNowBtn.disabled = true;
                addToCartBtn.classList.remove('btn-primary');
                buyNowBtn.classList.remove('btn-outline-primary');
                addToCartBtn.classList.add('btn-secondary');
                buyNowBtn.classList.add('btn-secondary');
            }

            // Fungsi untuk menampilkan error
            function showError(message) {
                errorDiv.textContent = message;
                errorDiv.classList.remove('d-none');
            }

            // Fungsi untuk mencari varian yang sesuai
            function findVariant() {
                const selectedValues = Object.values(selectedAttributes).filter(Boolean);

                if (selectedValues.length === variantData.attributes.length) {
                    const foundVariant = variantData.variants.find(variant => {
                        return selectedValues.every(val => variant.attributes.includes(val));
                    });

                    if (foundVariant) {
                        if (foundVariant.stock <= 0) {
                            showError('Varian ini sedang habis stok');
                        } else {
                            errorDiv.classList.add('d-none');
                        }
                    } else {
                        showError('Kombinasi varian tidak tersedia');
                    }

                    updateVariantInfo(foundVariant);
                    updateButtons(foundVariant);
                } else {
                    // Reset jika belum semua atribut dipilih
                    errorDiv.classList.add('d-none');
                    document.getElementById('selected-variant-info').classList.add('d-none');
                    disableButtons();
                }
            }

            // Fungsi untuk update tampilan info varian
            function updateVariantInfo(variant) {
                const infoDiv = document.getElementById('selected-variant-info');

                if (variant) {
                    currentVariant = variant;
                    infoDiv.classList.remove('d-none');

                    document.getElementById('variant-sku').textContent = variant.sku;
                    document.getElementById('variant-stock').textContent = variant.stock;

                    const hargaContainer = document.getElementById('variant-price');
                    const originalPrice = Number(variant.price);
                    const discount = Number(variant.discount ?? 0);
                    const finalPrice = originalPrice - (originalPrice * discount / 100);

                    let hargaHTML = '';

                    console.log(variant);

                    if (discount > 0) {
                        hargaHTML += `
                            <span class="text-muted text-decoration-line-through me-2">
                                ${originalPrice.toLocaleString('id-ID', { minimumFractionDigits: 0 })}
                            </span>
                            <span class="text-primary fw-bold">
                                ${finalPrice.toLocaleString('id-ID', { minimumFractionDigits: 0 })}
                            </span>
                            <small class="text-danger fw-bold ms-2">-${discount}%</small>
                        `;
                    } else {
                        hargaHTML += `
                            <span class="text-primary fw-bold">
                                ${originalPrice.toLocaleString('id-ID', { minimumFractionDigits: 0 })}
                            </span>
                        `;
                    }

                    hargaContainer.innerHTML = hargaHTML;

                    if (variant.image) {
                        document.getElementById('variant-image').src = `/storage/${variant.image}`;
                        document.getElementById('variant-image').style.display = 'block';
                    } else {
                        document.getElementById('variant-image').style.display = 'none';
                    }
                } else {
                    currentVariant = null;
                    infoDiv.classList.add('d-none');
                }
            }

            // Event listener untuk setiap select
            selectElements.forEach(select => {
                select.addEventListener('change', function() {
                    const attributeId = this.dataset.attributeId;
                    selectedAttributes[attributeId] = this.value ? parseInt(this.value) : null;
                    findVariant();
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Event listener untuk tombol Tambah ke Keranjang
            addToCartBtn.addEventListener('click', function() {
                if (!currentVariant && variantData.attributes.length > 0) {
                    showError('Silakan pilih varian terlebih dahulu');
                    return;
                }

                const productId = {{ $product->id }};
                const variantId = currentVariant ? currentVariant.id : null;
                const quantity = 1;

                // Kirim request AJAX
                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            variant_id: variantId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message);
                            updateCartCount(data.cart_count);
                        } else {
                            showToast(data.message, true);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan saat menambahkan ke keranjang', true);
                    });
            });

            // Fungsi untuk menampilkan toast notifikasi
            function showToast(message, isError = false) {
                const toast = document.getElementById('liveToast');
                const toastBody = toast.querySelector('.toast-body');
                const toastHeader = toast.querySelector('.toast-header');

                toastBody.textContent = message;

                if (isError) {
                    toastHeader.classList.add('bg-danger');
                    toastHeader.classList.remove('bg-success');
                } else {
                    toastHeader.classList.add('bg-success');
                    toastHeader.classList.remove('bg-danger');
                }

                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }

            // Fungsi untuk update jumlah keranjang
            function updateCartCount(count) {
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(el => {
                    el.textContent = count;
                });
            }
        });
    </script>

    <script></script>
@endsection
