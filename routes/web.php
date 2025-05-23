<?php

use App\Models\District;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerificationController;

Auth::routes();

Route::get('/verify', [VerificationController::class, 'showVerificationForm'])->name('verify');
Route::post('/verify', [VerificationController::class, 'verify'])->name('verify-post');
Route::post('/resend-otp', [VerificationController::class, 'resendOtp'])->name('resend.otp');

// ✅ Landing Page (Terbuka untuk semua)
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');
Route::get('/contact', [App\Http\Controllers\LandingController::class, 'contact'])->name('contact');
Route::get('/chat', [App\Http\Controllers\LandingController::class, 'chat'])->name('chat');


// ✅ Produk
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [App\Http\Controllers\ProductController::class, 'getAll'])->name('all');
    Route::get('/{productId}', [App\Http\Controllers\ProductController::class, 'getDetail'])->name('detail');
});

// ✅ Route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {

    // 🏠 Dashboard / Home
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // ✅ Keranjang Belanja (Cart)
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [App\Http\Controllers\CartController::class, 'getCartPage'])->name('index');
        Route::get('/checkout', [App\Http\Controllers\CartController::class, 'getCheckoutPage'])->name('checkout');
        Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout.store');

        // 🔄 API Cart (JSON response)
        Route::get('/items', [App\Http\Controllers\CartController::class, 'getCart'])->name('get');
        Route::post('/add', [App\Http\Controllers\CartController::class, 'addCart'])->name('add');
        Route::put('/update/{id}', [App\Http\Controllers\CartController::class, 'updateCart'])->name('update');
        Route::delete('/remove/{id}', [App\Http\Controllers\CartController::class, 'removeCart'])->name('remove');
    });

    // ✅ Pemesanan (Orders)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'getOrders'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\OrderController::class, 'getOrderDetail'])->name('detail');
        Route::post('/{id}/cancel', [App\Http\Controllers\OrderController::class, 'cancelOrder'])->name('cancel');
        Route::get('/success/{order}', [App\Http\Controllers\OrderController::class, 'success'])->name('success');
    });

    // ✅ Profil & Akun Pengguna
    Route::prefix('account')->name('profile.')->group(function () {
        Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'showProfileForm'])->name('show');
        Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('update');

        // 🔐 Ubah Password
        Route::get('/password', [App\Http\Controllers\ProfileController::class, 'showChangePasswordForm'])->name('password.change');
        Route::put('/password/update', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // ✅ Post Review
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    // ✅ Post Question
    Route::post('/products/{product}/questions', [App\Http\Controllers\ProductQuestionController::class, 'store'])
        ->name('product.questions.store');

    // ✅ Wishlist
    Route::get('/wishlist', [App\Http\Controllers\ProductController::class, 'getWishlist'])->name('products.wishlist');
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/check/{productId}', [App\Http\Controllers\WishlistController::class, 'check'])->name('wishlist.check');

    // ✅ Alamat Pengiriman
    Route::prefix('address')->name('address.')->group(function () {
        Route::get('/', [App\Http\Controllers\UserAddressController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\UserAddressController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\UserAddressController::class, 'store'])->name('store');
        Route::get('{address}/edit', [App\Http\Controllers\UserAddressController::class, 'edit'])->name('edit');
        Route::put('{address}', [App\Http\Controllers\UserAddressController::class, 'update'])->name('update');
        Route::delete('{address}', [App\Http\Controllers\UserAddressController::class, 'destroy'])->name('destroy');
    });

    // ✅ API Lokasi (Wilayah)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('regencies/{province_id}', function ($province_id) {
            return Regency::where('province_id', $province_id)->get();
        });

        Route::get('districts/{regency_id}', function ($regency_id) {
            return District::where('regency_id', $regency_id)->get();
        });

        Route::get('villages/{district_id}', function ($district_id) {
            return Village::where('district_id', $district_id)->get();
        });
    });
});
