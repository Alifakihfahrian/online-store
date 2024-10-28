<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function addToCart(Request $request, Product $product)
    {
        Log::info('Add to cart request received', ['product_id' => $product->id, 'quantity' => $request->input('quantity')]);
        $user = Auth::user();
        $quantity = $request->input('quantity', 1);

        $existingCartItem = $user->cart()->where('product_id', $product->id)->first();

        if ($existingCartItem) {
            $existingCartItem->increment('quantity', $quantity);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cartCount' => $this->getCartCount()
        ]);
    }

    public function updateCartItem(Request $request, Product $product)
    {
        $user = Auth::user();
        $quantity = $request->input('quantity', 0);

        $cartItem = $user->cart()->where('product_id', $product->id)->first();

        if ($cartItem) {
            if ($quantity > 0) {
                $cartItem->update(['quantity' => $quantity]);
            } else {
                $cartItem->delete();
            }
        }

        return response()->json([
            'message' => 'Keranjang berhasil diperbarui',
            'cartCount' => $this->getCartCount()
        ]);
    }

    public function getCartCount()
    {
        return Auth::user()->cart()->sum('quantity');
    }

    public function viewCart()
    {
        $user = Auth::user();
        $cartItems = $user->cart()->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.cart', compact('cartItems', 'total'));
    }

    public function clearCart()
    {
        $user = Auth::user();
        $user->cart()->delete();
        
        return response()->json([
            'message' => 'Keranjang berhasil dikosongkan'
        ]);
    }

    public function add(Request $request, $productId)
    {
        try {
            $product = Product::findOrFail($productId);
            $quantity = $request->input('quantity', 1);

            // Validasi stok
            if ($quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok yang tersedia'
                ]);
            }

            // Cek apakah produk sudah ada di keranjang
            $cartItem = Cart::where('user_id', auth()->id())
                           ->where('product_id', $productId)
                           ->first();

            if ($cartItem) {
                // Update quantity jika sudah ada
                $newQuantity = $cartItem->quantity + $quantity;
                if ($newQuantity > $product->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Total jumlah melebihi stok yang tersedia'
                    ]);
                }
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                // Tambah item baru ke keranjang
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }

            // Hitung total quantity di keranjang
            $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cartCount' => $cartCount // Ini akan mengirim total quantity terbaru
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan ke keranjang'
            ]);
        }
    }

    public function index()
    {
        // Ambil semua item di keranjang user yang sedang login
        $cartItems = Cart::with('product')
                        ->where('user_id', auth()->id())
                        ->get();

        return view('customer.cart', compact('cartItems'));
    }
}
