@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Kategori</h2>
        <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">+ Tambah</a>

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('categories.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Category Name</label>
                        <input type="text" id="search" name="search" class="form-control"
                            value="{{ request('search') }}" placeholder="Search by category name...">
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

                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
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

        @if ($categories->count())
            <div class="card">
                <div class="card-body">
                    <p class="text-muted">Total: {{ $categories->total() }} kategori</p>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $cat)
                                <tr>
                                    <td>{{ $cat->name }}</td>
                                    <td>{{ $cat->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('categories.edit', $cat) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('categories.destroy', $cat) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin hapus?')">Hapus</button>

                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="alert alert-info">Tidak ada kategori ditemukan.</div>
        @endif
    </div>
@endsection
