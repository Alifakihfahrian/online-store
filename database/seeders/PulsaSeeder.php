<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pulsa;

class PulsaSeeder extends Seeder
{
    public function run()
    {
        $pulsas = [
            ['provider' => 'Telkomsel', 'nominal' => 5000, 'price' => 5500],
            ['provider' => 'Telkomsel', 'nominal' => 10000, 'price' => 10500],
            ['provider' => 'Indosat', 'nominal' => 5000, 'price' => 5300],
            ['provider' => 'Indosat', 'nominal' => 10000, 'price' => 10300],
            ['provider' => 'XL', 'nominal' => 5000, 'price' => 5200],
            ['provider' => 'XL', 'nominal' => 10000, 'price' => 10200],
            // Tambahkan data lainnya sesuai kebutuhan
        ];

        foreach ($pulsas as $pulsa) {
            Pulsa::create($pulsa);
        }
    }
}
