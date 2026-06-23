@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Produk</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">+ Tambah</a>

    @if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($products->count())
    <table class="table">
        <tr><th>Nama</th><th>SKU</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
        @foreach($products as $prod)
        <tr>
            <td>{{ $prod->name }}</td>
            <td>{{ $prod->sku }}</td>
            <td>{{ $prod->category->name }}</td>
            <td>Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
            <td>{{ $prod->stock }}</td>
            <td>
                <a href="{{ route('products.edit', $prod) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('products.destroy', $prod) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    @endif
</div>
@endsection
