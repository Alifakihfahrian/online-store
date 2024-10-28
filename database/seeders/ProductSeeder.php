<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Elektronik
        $elektronik = Category::where('name', 'Elektronik')->first()->id;
        $products = [
            [
                'name' => 'Headphone Bluetooth',
                'description' => 'Headphone wireless dengan kualitas suara premium',
                'price' => 299000,
                'stock' => 100,
                'category_id' => $elektronik
            ],
            [
                'name' => 'Power Bank 10000mAh',
                'description' => 'Power bank dengan kapasitas besar dan fast charging',
                'price' => 199000,
                'stock' => 100,
                'category_id' => $elektronik
            ],
            [
                'name' => 'Mouse Wireless',
                'description' => 'Mouse ergonomis dengan koneksi wireless stabil',
                'price' => 89000,
                'stock' => 100,
                'category_id' => $elektronik
            ],
            [
                'name' => 'Keyboard Mechanical',
                'description' => 'Keyboard gaming dengan switch blue',
                'price' => 450000,
                'stock' => 100,
                'category_id' => $elektronik
            ],
            [
                'name' => 'Speaker Bluetooth',
                'description' => 'Speaker portable dengan bass yang powerful',
                'price' => 275000,
                'stock' => 100,
                'category_id' => $elektronik
            ],
            [
                'name' => 'Webcam HD',
                'description' => 'Webcam 1080p untuk meeting online',
                'price' => 225000,
                'stock' => 100,
                'category_id' => $elektronik
            ],
        ];

        // Alat Tulis
        $alatTulis = Category::where('name', 'Alat Tulis')->first()->id;
        $products = array_merge($products, [
            [
                'name' => 'Buku Tulis A5',
                'description' => 'Buku tulis dengan kertas berkualitas',
                'price' => 7000,
                'stock' => 100,
                'category_id' => $alatTulis
            ],
            [
                'name' => 'Pensil 2B',
                'description' => 'Pensil untuk menulis dan menggambar',
                'price' => 3500,
                'stock' => 100,
                'category_id' => $alatTulis
            ],
            [
                'name' => 'Pulpen Gel',
                'description' => 'Pulpen dengan tinta gel yang halus',
                'price' => 5000,
                'stock' => 100,
                'category_id' => $alatTulis
            ],
            [
                'name' => 'Penghapus',
                'description' => 'Penghapus karet lembut',
                'price' => 2500,
                'stock' => 100,
                'category_id' => $alatTulis
            ],
            [
                'name' => 'Correction Tape',
                'description' => 'Tip-ex bentuk pita 5mm x 6m',
                'price' => 8500,
                'stock' => 100,
                'category_id' => $alatTulis
            ],
            [
                'name' => 'Highlighter',
                'description' => 'Spidol stabilo warna-warni',
                'price' => 7500,
                'stock' => 100,
                'category_id' => $alatTulis
            ],
        ]);

        // Fashion
        $fashion = Category::where('name', 'Fashion')->first()->id;
        $products = array_merge($products, [
            [
                'name' => 'Kaos Polos Premium',
                'description' => 'Kaos dengan bahan cotton combed 30s',
                'price' => 89000,
                'stock' => 100,
                'category_id' => $fashion
            ],
            [
                'name' => 'Celana Jeans',
                'description' => 'Celana jeans dengan bahan stretch nyaman',
                'price' => 199000,
                'stock' => 100,
                'category_id' => $fashion
            ],
            [
                'name' => 'Topi Baseball',
                'description' => 'Topi dengan bahan berkualitas',
                'price' => 45000,
                'stock' => 100,
                'category_id' => $fashion
            ],
            [
                'name' => 'Kemeja Flanel',
                'description' => 'Kemeja flanel lengan panjang',
                'price' => 159000,
                'stock' => 100,
                'category_id' => $fashion
            ],
            [
                'name' => 'Jaket Denim',
                'description' => 'Jaket jeans dengan washing',
                'price' => 249000,
                'stock' => 100,
                'category_id' => $fashion
            ],
            [
                'name' => 'Celana Chino',
                'description' => 'Celana chino slim fit',
                'price' => 179000,
                'stock' => 100,
                'category_id' => $fashion
            ],
        ]);

        // Makanan & Minuman
        $makananMinuman = Category::where('name', 'Makanan & Minuman')->first()->id;
        $products = array_merge($products, [
            [
                'name' => 'Kopi Arabika',
                'description' => 'Kopi arabika premium 250gr',
                'price' => 85000,
                'stock' => 100,
                'category_id' => $makananMinuman
            ],
            [
                'name' => 'Coklat Premium',
                'description' => 'Coklat dark premium 100gr',
                'price' => 35000,
                'stock' => 100,
                'category_id' => $makananMinuman
            ],
            [
                'name' => 'Green Tea Matcha',
                'description' => 'Green tea matcha bubuk 50gr',
                'price' => 45000,
                'stock' => 100,
                'category_id' => $makananMinuman
            ],
            [
                'name' => 'Madu Murni',
                'description' => 'Madu murni flores 500ml',
                'price' => 95000,
                'stock' => 100,
                'category_id' => $makananMinuman
            ],
            [
                'name' => 'Granola',
                'description' => 'Granola mix 400gr',
                'price' => 55000,
                'stock' => 100,
                'category_id' => $makananMinuman
            ],
            [
                'name' => 'Cold Brew Coffee',
                'description' => 'Kopi cold brew 1L',
                'price' => 65000,
                'stock' => 100,
                'category_id' => $makananMinuman
            ],
        ]);

        // Kesehatan
        $kesehatan = Category::where('name', 'Kesehatan')->first()->id;
        $products = array_merge($products, [
            [
                'name' => 'Masker KN95',
                'description' => 'Masker KN95 5 lapis',
                'price' => 15000,
                'stock' => 100,
                'category_id' => $kesehatan
            ],
            [
                'name' => 'Hand Sanitizer',
                'description' => 'Hand sanitizer 100ml',
                'price' => 25000,
                'stock' => 100,
                'category_id' => $kesehatan
            ],
            [
                'name' => 'Vitamin C',
                'description' => 'Vitamin C 1000mg isi 30 tablet',
                'price' => 75000,
                'stock' => 100,
                'category_id' => $kesehatan
            ],
            [
                'name' => 'Multivitamin',
                'description' => 'Multivitamin lengkap 60 kapsul',
                'price' => 125000,
                'stock' => 100,
                'category_id' => $kesehatan
            ],
            [
                'name' => 'Minyak Kayu Putih',
                'description' => 'Minyak kayu putih 60ml',
                'price' => 35000,
                'stock' => 100,
                'category_id' => $kesehatan
            ],
            [
                'name' => 'Kotak P3K',
                'description' => 'Kotak P3K lengkap',
                'price' => 185000,
                'stock' => 100,
                'category_id' => $kesehatan
            ],
        ]);

        // Olahraga
        $olahraga = Category::where('name', 'Olahraga')->first()->id;
        $products = array_merge($products, [
            [
                'name' => 'Yoga Mat',
                'description' => 'Matras yoga anti slip',
                'price' => 150000,
                'stock' => 100,
                'category_id' => $olahraga
            ],
            [
                'name' => 'Dumbell 5kg',
                'description' => 'Dumbell vinyl 5kg/pair',
                'price' => 225000,
                'stock' => 100,
                'category_id' => $olahraga
            ],
            [
                'name' => 'Tali Skipping',
                'description' => 'Tali skipping dengan counter',
                'price' => 45000,
                'stock' => 100,
                'category_id' => $olahraga
            ],
            [
                'name' => 'Botol Minum Olahraga',
                'description' => 'Botol minum 750ml',
                'price' => 85000,
                'stock' => 100,
                'category_id' => $olahraga
            ],
        ]);

        // Update semua stok menjadi 100
        foreach ($products as &$product) {
            $product['stock'] = 100;
        }

        // Cara cepat untuk mengupdate semua produk yang sudah ada
        Product::query()->update(['stock' => 100]);

        // Atau jika ingin membuat produk baru dengan stok 100
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
