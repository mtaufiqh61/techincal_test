@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Buat Penjualan</h2>
    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label>Produk</label>
            <div id="items">
                <div class="row mb-2 item-row">
                    <div class="col-md-5">
                        <select name="products[]" class="form-control product-select" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $prod)
                            <option value="{{ $prod->id }}" data-stock="{{ $prod->stock }}">{{ $prod->name }} (Stok: {{ $prod->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="quantities[]" class="form-control qty-input" placeholder="Qty" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm removeItemBtn" style="display:none;">X Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="addItemBtn" class="btn btn-secondary btn-sm mb-3">+ Tambah Item</button>
        <br>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
const products = {!! json_encode($products) !!};

function updateAllSelects() {
    const selectedProducts = Array.from(document.querySelectorAll('.product-select'))
        .map(select => select.value)
        .filter(val => val !== '');

    document.querySelectorAll('.product-select').forEach(select => {
        const currentValue = select.value;
        Array.from(select.options).forEach(option => {
            if (option.value === '') return;
            option.disabled = selectedProducts.includes(option.value) && option.value !== currentValue;
        });
    });
}

function validateQuantities() {
    let isValid = true;
    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const productId = select.value;
        const qty = parseInt(qtyInput.value) || 0;

        if (!productId) return;

        const product = products.find(p => p.id == productId);
        if (qty > product.stock) {
            qtyInput.classList.add('is-invalid');
            isValid = false;
        } else {
            qtyInput.classList.remove('is-invalid');
        }
    });
    return isValid;
}

document.getElementById('addItemBtn').addEventListener('click', function() {
    const itemsContainer = document.getElementById('items');
    const rowCount = document.querySelectorAll('.item-row').length;
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 item-row';
    newRow.innerHTML = `
        <div class="col-md-5">
            <select name="products[]" class="form-control product-select" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $prod)
                <option value="{{ $prod->id }}" data-stock="{{ $prod->stock }}">{{ $prod->name }} (Stok: {{ $prod->stock }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <input type="number" name="quantities[]" class="form-control qty-input" placeholder="Qty" min="1" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm removeItemBtn">X Hapus</button>
        </div>
    `;
    itemsContainer.appendChild(newRow);

    const newSelect = newRow.querySelector('.product-select');
    const newQtyInput = newRow.querySelector('.qty-input');
    const removeBtn = newRow.querySelector('.removeItemBtn');

    newSelect.addEventListener('change', function() {
        updateAllSelects();
        validateQuantities();
    });

    newQtyInput.addEventListener('input', validateQuantities);

    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        newRow.remove();
        updateAllSelects();
    });

    updateAllSelects();

    document.querySelectorAll('.removeItemBtn').forEach(btn => {
        btn.style.display = rowCount > 0 ? 'block' : 'none';
    });
});

document.querySelectorAll('.product-select').forEach(select => {
    select.addEventListener('change', function() {
        updateAllSelects();
        validateQuantities();
    });
});

document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('input', validateQuantities);
});

document.querySelectorAll('.removeItemBtn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        this.closest('.item-row').remove();
        updateAllSelects();
    });
});

document.querySelector('form').addEventListener('submit', function(e) {
    if (!validateQuantities()) {
        e.preventDefault();
        alert('Qty tidak boleh melebihi stok!');
    }
});
</script>
@endsection
