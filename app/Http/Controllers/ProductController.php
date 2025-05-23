<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariantValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Menampilkan semua produk yang tersedia.
     *
     * @return View Halaman daftar semua produk.
     */
    public function getAll(Request $request)
    {
        // Ambil data kategori dan brand untuk dropdown
        $categories = Category::all();
        $brands = Brand::all();

        // Ambil query filter dari request
        $categoryId = $request->input('category');
        $brandId = $request->input('brand');
        $sortBy = $request->input('sort_by');
        $search = $request->input('search');
        $isDiscount = $request->boolean('is_discount'); // Menggunakan boolean() untuk konversi otomatis

        // Query produk berdasarkan filter
        $query = Product::query()
            ->with(['attributes.values', 'variants.attributeValues']);

        // Filter berdasarkan kategori
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Filter berdasarkan brand
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        // Filter berdasarkan keyword
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filter produk diskon
        if ($isDiscount) {
            $query->where(function ($q) {
                // Produk dengan diskon langsung
                $q->where('discount', '>', 0)
                    // Atau produk yang memiliki varian dengan diskon
                    ->orWhereHas('variants', function ($variantQuery) {
                        $variantQuery->where('discount', '>', 0);
                    });
            });
        }

        // Sorting produk
        if ($sortBy == 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sortBy == 'name_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($sortBy == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sortBy == 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->paginate(8);

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Menampilkan detail produk berdasarkan ID.
     *
     * @param int $productId ID produk yang akan ditampilkan.
     * @return View Halaman detail produk.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika produk tidak ditemukan.
     */
    public function getDetail(int $productId): View
    {
        $product = Product::with(['attributes.values', 'variants.attributeValues'])
            ->findOrFail($productId);

        // Format data untuk JavaScript
        $variantData = [
            'attributes' => $product->attributes->map(function ($attr) {
                return [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'values' => $attr->values->map(function ($val) {
                        return [
                            'id' => $val->id,
                            'value' => $val->value
                        ];
                    })
                ];
            }),
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'discount' => $variant->discount,
                    'image' => $variant->image,
                    'attributes' => $variant->attributeValues->pluck('id')->toArray()
                ];
            })
        ];

        return view('products.detail', [
            'product' => $product,
            'variants' => $product->variants,
            'variantData' => $variantData
        ]);
    }

    /**
     * Menampilkan daftar produk dalam wishlist user
     *
     * @return View Halaman daftar produk wishlist
     */
    public function getWishlist(Request $request)
    {
        // Ambil data kategori dan brand untuk dropdown filter
        $categories = Category::all();
        $brands = Brand::all();

        // Ambil query filter dari request
        $categoryId = $request->input('category');
        $brandId = $request->input('brand');
        $sortBy = $request->input('sort_by');
        $search = $request->input('search');
        $isDiscount = $request->boolean('is_discount');

        // Query produk dalam wishlist user
        // $idUser = Auth::id();
        $query = Product::whereHas('wishlists', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['attributes.values', 'variants.attributeValues']);

        // Filter berdasarkan kategori
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Filter berdasarkan brand
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        // Filter berdasarkan keyword
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filter produk diskon
        if ($isDiscount) {
            $query->where(function ($q) {
                $q->where('discount', '>', 0)
                    ->orWhereHas('variants', function ($variantQuery) {
                        $variantQuery->where('discount', '>', 0);
                    });
            });
        }

        // Sorting produk
        if ($sortBy == 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sortBy == 'name_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($sortBy == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sortBy == 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->paginate(8);

        return view('products.wishlist', compact('products', 'categories', 'brands'));
    }
}
