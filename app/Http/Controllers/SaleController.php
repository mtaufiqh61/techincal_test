<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sales = Sale::with('user')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'products' => 'required|array|min:1',
            'quantities' => 'required|array'
        ]);

        // Validasi: produk tidak duplicate
        if (count($request->products) !== count(array_unique($request->products))) {
            return back()->withErrors(['error' => 'Produk tidak boleh dipilih 2x']);
        }

        // Validasi: qty tidak boleh melebihi stok
        foreach ($request->products as $key => $product_id) {
            $product = Product::find($product_id);
            $qty = $request->quantities[$key];
            if ($qty > $product->stock) {
                return back()->withErrors(["error" => "{$product->name}: qty ({$qty}) melebihi stok ({$product->stock})"]);
            }
        }

        $total = 0;
        $sale = Sale::create([
            'user_id' => auth()->id(),
            'sale_date' => $request->sale_date,
            'total' => 0
        ]);

        foreach ($request->products as $key => $product_id) {
            $product = Product::find($product_id);
            $quantity = $request->quantities[$key];
            $subtotal = $product->price * $quantity;
            $total += $subtotal;

            $sale->items()->create([
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $subtotal
            ]);

            $product->decrement('stock', $quantity);
        }

        $sale->update(['total' => $total]);
        return redirect()->route('sales.show', $sale)->with('success', 'Penjualan berhasil disimpan');
    }

    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $products = Product::all();
        return view('sales.edit', compact('sale', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'sale_date' => 'required|date',
            'products' => 'required|array|min:1',
            'quantities' => 'required|array'
        ]);

        // Validasi: produk tidak duplicate
        if (count($request->products) !== count(array_unique($request->products))) {
            return back()->withErrors(['error' => 'Produk tidak boleh dipilih 2x']);
        }

        // Validasi: qty tidak boleh melebihi stok (termasuk stok sebelumnya)
        foreach ($request->products as $key => $product_id) {
            $product = Product::find($product_id);
            $qty = $request->quantities[$key];

            // Restore stok lama terlebih dahulu
            $oldQty = $sale->items->where('product_id', $product_id)->sum('quantity');
            $availableStock = $product->stock + $oldQty;

            if ($qty > $availableStock) {
                return back()->withErrors(["error" => "{$product->name}: qty ({$qty}) melebihi stok ({$availableStock})"]);
            }
        }

        // Restore stok lama
        foreach ($sale->items as $item) {
            $item->product->increment('stock', $item->quantity);
            $item->delete();
        }

        $total = 0;
        foreach ($request->products as $key => $product_id) {
            $product = Product::find($product_id);
            $quantity = $request->quantities[$key];
            $subtotal = $product->price * $quantity;
            $total += $subtotal;

            $sale->items()->create([
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $subtotal
            ]);

            $product->decrement('stock', $quantity);
        }

        $sale->update(['sale_date' => $request->sale_date, 'total' => $total]);
        return redirect()->route('sales.show', $sale)->with('success', 'Penjualan berhasil diubah');
    }

    public function destroy(Sale $sale)
    {
        // Restore stok
        foreach ($sale->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dihapus');
    }
}