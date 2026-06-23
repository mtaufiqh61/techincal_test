<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $today = now()->toDateString();

        $total_sales_today = Sale::whereDate('sale_date', $today)->sum('total');
        $total_transactions_today = Sale::whereDate('sale_date', $today)->count();
        $low_stock_products = Product::where('stock', '<', 10)->count();
        $total_products = Product::count();

        $sales_this_week = Sale::whereBetween('sale_date', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString()
        ])->get();

        return view('home', compact(
            'total_sales_today',
            'total_transactions_today',
            'low_stock_products',
            'total_products',
            'sales_this_week'
        ));
    }
}