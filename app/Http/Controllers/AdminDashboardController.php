<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        $products = $query->latest()->paginate(10);
        $categories = Category::all();
        
        return view('admin.dashboard', compact('products', 'categories'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.create-product', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        
        // Simpan halaman sebelumnya di session
        session(['previousPage' => request()->get('page', 1)]);
        
        return view('admin.edit-product', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $validatedData = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'image' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Update data produk
            $product->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'stock' => $validatedData['stock'],
                'category_id' => $validatedData['category_id']
            ]);

            // Handle upload gambar jika ada
            if ($request->hasFile('image')) {
                if ($product->image && Storage::exists('public/' . $product->image)) {
                    Storage::delete('public/' . $product->image);
                }
                
                $image = $request->file('image');
                $imagePath = $image->store('products', 'public');
                $product->update(['image' => $imagePath]);
            }

            // Ambil halaman sebelumnya dari session
            $previousPage = session('previousPage', 1);
            
            // Redirect ke halaman sebelumnya
            return redirect()->route('admin.dashboard', ['page' => $previousPage])
                            ->with('success', 'Produk berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function deleteProduct(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil dihapus');
    }
}
