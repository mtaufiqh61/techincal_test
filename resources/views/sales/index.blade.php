@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Penjualan</h2>
    <a href="{{ route('sales.create') }}" class="btn btn-primary mb-3">+ Buat Penjualan</a>

    @if($sales->count())
    <table class="table">
        <tr><th>ID</th><th>Tanggal</th><th>User</th><th>Total</th><th>Aksi</th></tr>
        @foreach($sales as $sale)
        <tr>
            <td>#{{ $sale->id }}</td>
            <td>{{ $sale->sale_date }}</td>
            <td>{{ $sale->user->name }}</td>
            <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
            <td>
                <a href="{{ route('sales.show', $sale) }}" class="btn btn-info btn-sm">Lihat</a>
                <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('sales.destroy', $sale) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {{ $sales->links() }}
    @endif
</div>
@endsection
