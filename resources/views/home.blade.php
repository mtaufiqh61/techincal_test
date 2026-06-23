@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard</h2>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Penjualan Hari Ini</h5>
                    <p class="card-text display-4">Rp {{ number_format($total_sales_today, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Hari Ini</h5>
                    <p class="card-text display-4">{{ $total_transactions_today }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Stok Rendah</h5>
                    <p class="card-text display-4">{{ $low_stock_products }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Produk</h5>
                    <p class="card-text display-4">{{ $total_products }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h4>Penjualan Minggu Ini</h4>
            <table class="table">
                <tr><th>ID</th><th>Tanggal</th><th>User</th><th>Total</th></tr>
                @forelse($sales_this_week as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->sale_date }}</td>
                    <td>{{ $sale->user->name }}</td>
                    <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Belum ada penjualan minggu ini</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">Kelola Kategori</a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Kelola Produk</a>
            <a href="{{ route('sales.index') }}" class="btn btn-outline-primary">Lihat Penjualan</a>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">+ Buat Penjualan</a>
        </div>
    </div>
</div>
@endsection
