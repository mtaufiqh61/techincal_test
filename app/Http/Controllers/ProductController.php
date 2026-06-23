<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'sku' => 'required|unique:products',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);
        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambah');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);
        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Produk berhasil diubah');
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('products.index')->with('error', 'Tidak bisa menghapus produk—ada transaksi penjualan yang menggunakan produk ini. Hapus transaksi terlebih dahulu.');
        }
    }
}
