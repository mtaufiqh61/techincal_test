<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Category::query();

        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Filter by category name
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filter created_at range
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $categories = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact(
            'categories',
            'search',
            'startDate',
            'endDate'
        ));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories']);
        Category::create($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambah');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|unique:categories,name,' . $category->id]);
        $category->update($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diubah');
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('categories.index')->with('error', 'Tidak bisa menghapus kategori—ada produk yang menggunakan kategori ini. Hapus produk terlebih dahulu.');
        }
    }
}
