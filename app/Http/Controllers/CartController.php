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

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|numeric|min:1'
            ]);

            $user = Auth::user();
            $product = Product::findOrFail($validated['product_id']);
            $quantity = $validated['quantity'];

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
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cartCount' => $this->getCartCount()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 422);
        }
    }

    public function index()
    {
        $cartItems = Cart::with('product')
                        ->where('user_id', auth()->id())
                        ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.cart', compact('cartItems', 'total'));
    }
}
