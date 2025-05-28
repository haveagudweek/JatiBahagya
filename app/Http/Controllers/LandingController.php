<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function index()
    {
           \Illuminate\Support\Facades\Log::info('DEBUGGING CLOUDINARY CONFIG:', [
        'config_cloudinary' => config('cloudinary', 'File config/cloudinary.php TIDAK DITEMUKAN'),
        'env_cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ENV CLOUDINARY_CLOUD_NAME KOSONG'),
        'env_api_key' => env('CLOUDINARY_API_KEY', 'ENV CLOUDINARY_API_KEY KOSONG'),
        // Jangan log API Secret untuk keamanan, cukup cek keberadaannya
        'env_api_secret_exists' => !empty(env('CLOUDINARY_API_SECRET')), 
    ]);
        // Ambil 3 kategori dengan jumlah produk terbanyak
        $preview_categories = \App\Models\Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(10)
            ->get();

        // Ambil 10 produk secara acak untuk Flash Sale
        $product_flash_sale = Product::with('variants')
            ->where(function ($query) {
                $query->where('discount', '>', 0)
                    ->orWhereHas('variants', function ($query) {
                        $query->where('discount', '>', 0);
                    });
            })
            ->with(['variants' => function ($query) {
                $query->orderByDesc('discount');
            }])
            ->orderByDesc('discount')
            ->limit(10)
            ->get();

        // Ambil 10 produk terbaik berdasarkan stok terbanyak (atau bisa pakai kriteria lain)
        $best_items = Product::where('status', 'active')
            ->orderBy('stock', 'desc')
            ->limit(10)
            ->get();

        // Ambil 3 kategori dengan jumlah produk terbanyak
        $top_categories = \App\Models\Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(3)
            ->get();

        // Ambil produk dari 3 kategori yang terpilih
        $category_products = [];
        foreach ($top_categories as $category) {
            $category_products[$category->id] = Product::where('category_id', $category->id)
                ->limit(8)
                ->get();
        }

        // Ambil produk berdasarkan is_new_product true dengan limit 4 atau 8 tergantung jumlahnya
        $new_products = Product::where('is_new_product', true)
            ->when(Product::where('is_new_product', true)->count() > 8, function ($query) {
                return $query->limit(8);
            }, function ($query) {
                return $query->limit(4);
            })
            ->get();

        return view('landing.index', compact(
            'product_flash_sale',
            'best_items',
            'top_categories',
            'category_products',
            'preview_categories',
            'new_products'
        ));
    }


    public function contact()
    {
        return view('pages.contact');
    }

    public function chat()
    {
        $products = Product::latest()->take(10)->get();

        return view('pages.chat', compact('products'));
    }
}
