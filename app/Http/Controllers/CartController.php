<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     *
     * @return \Illuminate\View\View
     */
    public function getCartPage()
    {
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->get();

        return view('transactions.cart', compact('cartItems'));
    }

    /**
     * Menampilkan halaman keranjang belanja.
     *
     * @return \Illuminate\View\View
     */
    public function getCheckoutPage()
    {
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->get();

        return view('transactions.checkout', compact('cartItems'));
    }

    /**
     * Mengambil data keranjang dalam format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCart()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        return response()->json(['cart' => $cartItems], 200);
    }

    /**
     * Menambahkan produk ke dalam keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id,product_id,' . $request->product_id,
            'quantity' => 'required|integer|min:1'
        ]);

        // Cari produk dengan relasi variants
        $product = Product::with('variants')->findOrFail($request->product_id);

        // Validasi untuk produk dengan varian
        if ($product->variants->isNotEmpty() && !$request->variant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan pilih varian terlebih dahulu'
            ], 422);
        }

        // Cari varian jika ada
        $variant = $request->variant_id
            ? ProductVariant::where('id', $request->variant_id)
            ->where('product_id', $product->id)
            ->first()
            : null;

        // Validasi varian
        if ($request->variant_id && !$variant) {
            return response()->json([
                'success' => false,
                'message' => 'Varian tidak valid untuk produk ini'
            ], 422);
        }

        // Cek apakah item sudah ada di keranjang
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('variant_id', $variant ? $variant->id : null)
            ->first();

        // Hitung quantity baru jika item sudah ada
        $newQuantity = $existingCart
            ? $existingCart->quantity + $request->quantity
            : $request->quantity;

        // Validasi stok
        if ($variant) {
            if ($newQuantity > $variant->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok varian tidak mencukupi',
                    'available_stock' => $variant->stock,
                    'current_cart_quantity' => $existingCart ? $existingCart->quantity : 0,
                    'max_allowed' => $variant->stock - ($existingCart ? $existingCart->quantity : 0)
                ], 422);
            }
        } else {
            if ($newQuantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak mencukupi',
                    'available_stock' => $product->stock,
                    'current_cart_quantity' => $existingCart ? $existingCart->quantity : 0,
                    'max_allowed' => $product->stock - ($existingCart ? $existingCart->quantity : 0)
                ], 422);
            }
        }

        // Update atau create item keranjang
        if ($existingCart) {
            $existingCart->update(['quantity' => $newQuantity]);
            $message = 'Quantity produk di keranjang diperbarui';
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
                'quantity' => $request->quantity,
                'price' => $variant ? $variant->final_price : $product->final_price,
                'options' => [
                    'image' => $variant && $variant->image
                        ? $variant->image
                        : $product->main_image,
                    'variant_name' => $variant ? $this->getVariantName($variant) : null
                ]
            ]);
            $message = 'Produk berhasil ditambahkan ke keranjang';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'cart_count' => Cart::where('user_id', Auth::id())->sum('quantity')
        ]);
    }


    /**
     * Memperbarui jumlah produk dalam keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id ID item keranjang
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Dapatkan item keranjang
        $cartItem = Cart::with(['product', 'product.variants'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);


        // return (json_encode($cartItem));

        // Validasi stok berdasarkan varian atau produk utama
        if ($cartItem->variant_id) {
            $variant = $cartItem->product->variants->find($cartItem->variant_id);

            if ($request->quantity > $variant->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok varian yang tersedia',
                    'available_stock' => $variant->stock,
                    'max_allowed' => $variant->stock
                ], 400);
            }
        } else {
            // Validasi untuk produk tanpa varian
            if ($request->quantity > $cartItem->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok produk yang tersedia',
                    'available_stock' => $cartItem->product->stock,
                    'max_allowed' => $cartItem->product->stock,
                    'cart_item' => $cartItem
                ], 400);
            }
        }

        // Update quantity
        $cartItem->update(['quantity' => $request->quantity]);

        // Hitung ulang total keranjang
        $cartTotal = Cart::where('user_id', Auth::id())->sum(DB::raw('price * quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk diperbarui',
            'new_quantity' => $cartItem->quantity,
            'item_subtotal' => number_format($cartItem->price * $cartItem->quantity, 0, ',', '.'),
            'cart_total' => number_format($cartTotal, 0, ',', '.'),
            'cart_count' => Cart::where('user_id', Auth::id())->sum('quantity'),
            'original_price' => $cartItem->variant_id
                ? $cartItem->product->variants->find($cartItem->variant_id)->price
                : $cartItem->product->price,
            'discount' => $cartItem->variant_id
                ? $cartItem->product->variants->find($cartItem->variant_id)->discount
                : $cartItem->product->discount,

        ], 200);
    }


    /**
     * Menghapus produk dari keranjang.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCart($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Produk dihapus dari keranjang'], 200);
    }

    private function getVariantName($variant)
    {
        return $variant->attributeValues->map(function ($value) {
            return $value->attribute->name . ': ' . $value->value;
        })->implode(', ');
    }
}
