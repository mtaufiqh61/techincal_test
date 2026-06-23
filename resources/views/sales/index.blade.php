@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Penjualan</h2>

    <a href="{{ route('sales.create') }}" class="btn btn-primary mb-3">
        + Buat Penjualan
    </a>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('sales.index') }}" class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label for="user_name" class="form-label">User Name</label>
                    <input
                        type="text"
                        id="user_name"
                        name="user_name"
                        class="form-control"
                        value="{{ request('user_name') }}"
                        placeholder="Search user name..."
                    >
                </div>

                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        class="form-control"
                        value="{{ request('start_date') }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        class="form-control"
                        value="{{ request('end_date') }}"
                    >
                </div>

                <div class="col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-secondary">
                            Filter
                        </button>

                        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
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

    @if ($sales->count())
        <div class="card">
            <div class="card-body">

                <p class="text-muted">
                    Total: {{ $sales->total() }} penjualan
                </p>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>#{{ $sale->id }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}
                                </td>
                                <td>{{ $sale->user->name }}</td>
                                <td>
                                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                                </td>
                                <td>
                                    <a href="{{ route('sales.show', $sale) }}"
                                        class="btn btn-info btn-sm">
                                        Lihat
                                    </a>

                                    <a href="{{ route('sales.edit', $sale) }}"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('sales.destroy', $sale) }}"
                                        method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-danger btn-sm"
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
            {{ $sales->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="alert alert-info">
            Tidak ada data penjualan ditemukan.
        </div>
    @endif
</div>
@endsection
