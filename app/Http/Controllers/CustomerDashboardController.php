<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedCategory = $request->input('category');
        
        $query = Product::query();

        // Filter berdasarkan pencarian
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Filter berdasarkan kategori
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory);
        }

        $products = $query->where('stock', '>', 0)->paginate(12);
        $categories = Category::all();

        return view('customer.dashboard', compact('products', 'search', 'categories', 'selectedCategory'));
    }
}
