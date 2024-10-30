<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PulsaController extends Controller
{
    public function index()
    {
        return view('customer.pulsa.index');
    }

    public function showDetail(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'provider' => 'required|string',
            'nominal' => 'required|numeric|min:1000',
        ]);

        $phoneNumber = $request->phone_number;
        $provider = $request->provider;
        $nominal = $request->nominal;
        $price = $this->calculatePrice($nominal);

        return view('customer.pulsa.detail', compact('phoneNumber', 'provider', 'nominal', 'price'));
    }

    public function pay(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'provider' => 'required|string',
            'nominal' => 'required|numeric',
        ]);

        // Logika pembayaran di sini
        // ...

        // Return response JSON
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran pulsa berhasil!'
        ]);
    }

    public function redirect(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'provider' => 'required|string',
            'nominal' => 'required|numeric|min:1000',
        ]);

        return view('customer.pulsa.redirect');
    }

    private function calculatePrice($nominal)
    {
        // Contoh perhitungan harga: nominal + biaya admin (misalnya 1000)
        return $nominal + 1000;
    }
}
