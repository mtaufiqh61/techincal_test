@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Produk</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">+ Tambah</a>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label for="search" class="form-label">Product Name</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Search product name..."
                    >
                </div>

                <div class="col-md-2">
                    <label for="min_price" class="form-label">Min Price</label>
                    <input
                        type="number"
                        id="min_price"
                        name="min_price"
                        class="form-control"
                        min="0"
                        value="{{ request('min_price') }}"
                    >
                </div>

                <div class="col-md-2">
                    <label for="max_price" class="form-label">Max Price</label>
                    <input
                        type="number"
                        id="max_price"
                        name="max_price"
                        class="form-control"
                        min="0"
                        value="{{ request('max_price') }}"
                    >
                </div>

                <div class="col-md-2">
                    <label for="min_stock" class="form-label">Min Stock</label>
                    <input
                        type="number"
                        id="min_stock"
                        name="min_stock"
                        class="form-control"
                        min="0"
                        value="{{ request('min_stock') }}"
                    >
                </div>

                <div class="col-md-2">
                    <label for="max_stock" class="form-label">Max Stock</label>
                    <input
                        type="number"
                        id="max_stock"
                        name="max_stock"
                        class="form-control"
                        min="0"
                        value="{{ request('max_stock') }}"
                    >
                </div>

                <div class="col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-secondary">
                            Filter
                        </button>

                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($products->count())
        <div class="card">
            <div class="card-body">
                <p class="text-muted">
                    Total: {{ $products->total() }} produk
                </p>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>SKU</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $prod)
                            <tr>
                                <td>{{ $prod->name }}</td>
                                <td>{{ $prod->sku }}</td>
                                <td>{{ $prod->category->name }}</td>
                                <td>Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                                <td>{{ $prod->stock }}</td>
                                <td>
                                    <a href="{{ route('products.edit', $prod) }}"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('products.destroy', $prod) }}"
                                        method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="alert alert-info">
            Tidak ada produk ditemukan.
        </div>
    @endif
</div>
@endsection
