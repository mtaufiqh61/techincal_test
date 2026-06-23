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

    public function index(Request $request)
    {
        $query = Product::with('category');

        $search = $request->get('search');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $minStock = $request->get('min_stock');
        $maxStock = $request->get('max_stock');

        // Search by product name
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filter price range
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', $maxPrice);
        }

        // Filter stock range
        if ($minStock !== null && $minStock !== '') {
            $query->where('stock', '>=', $minStock);
        }

        if ($maxStock !== null && $maxStock !== '') {
            $query->where('stock', '<=', $maxStock);
        }

        $products = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact(
            'products',
            'search',
            'minPrice',
            'maxPrice',
            'minStock',
            'maxStock'
        ));
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
