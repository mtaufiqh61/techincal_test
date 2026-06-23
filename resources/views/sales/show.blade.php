@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Penjualan #{{ $sale->id }}</h2>
    <p><strong>Tanggal:</strong> {{ $sale->sale_date }} | <strong>User:</strong> {{ $sale->user->name }}</p>

    <table class="table">
        <tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>
        @foreach($sale->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr><td colspan="3"><strong>Total</strong></td><td><strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong></td></tr>
    </table>

    <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
