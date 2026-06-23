@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Kategori</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">+ Tambah</a>

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

    @if($categories->count())
    <table class="table">
        <tr><th>Nama</th><th>Aksi</th></tr>
        @foreach($categories as $cat)
        <tr>
            <td>{{ $cat->name }}</td>
            <td>
                <a href="{{ route('categories.edit', $cat) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('categories.destroy', $cat) }}" method="POST" style="display:inline;">
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
