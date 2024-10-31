<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Ambil cart items dengan relasi product
        $cartItems = Cart::with(['product', 'product.category'])
                        ->where('user_id', $user->id)
                        ->get();

        // Hitung total
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.checkout', compact('cartItems', 'total'));
    }
}
