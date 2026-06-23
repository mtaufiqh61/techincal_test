@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Penjualan #{{ $sale->id }}</h2>
    <form action="{{ route('sales.update', $sale) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="sale_date" class="form-control" value="{{ $sale->sale_date }}" required>
        </div>
        <div class="mb-3">
            <label>Produk</label>
            <div id="items">
                @foreach($sale->items as $item)
                <div class="row mb-2 item-row">
                    <div class="col-md-5">
                        <select name="products[]" class="form-control product-select" data-original-product="{{ $item->product_id }}" data-original-qty="{{ $item->quantity }}" required>
                            <option value="">Pilih Produk</option>
                            @foreach($products as $prod)
                            <option value="{{ $prod->id }}" data-stock="{{ $prod->stock }}" {{ $prod->id == $item->product_id ? 'selected' : '' }}>{{ $prod->name }} (Stok: {{ $prod->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="quantities[]" class="form-control qty-input" value="{{ $item->quantity }}" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm removeItemBtn">X Hapus</button>
                    </div>
                </div>
                @endforeach
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

function getAvailableStock(productId, currentQty) {
    const product = products.find(p => p.id == productId);
    if (!product) return 0;

    let usedQty = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('.product-select');
        const input = row.querySelector('.qty-input');
        if (select.value == productId && input !== event.target) {
            usedQty += parseInt(input.value) || 0;
        }
    });

    return product.stock + (currentQty || 0) - usedQty;
}

function updateAllSelects() {
    const selectedProducts = Array.from(document.querySelectorAll('.product-select'))
        .map((select, idx) => ({
            value: select.value,
            index: idx
        }))
        .filter(item => item.value !== '');

    document.querySelectorAll('.product-select').forEach((select, idx) => {
        const currentValue = select.value;
        Array.from(select.options).forEach(option => {
            if (option.value === '') return;
            const isDuplicate = selectedProducts.some(p => p.value === option.value && p.index !== idx);
            option.disabled = isDuplicate;
        });
    });
}

function validateQuantities() {
    let isValid = true;
    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const productId = select.value;
        const currentQty = parseInt(qtyInput.value) || 0;
        const originalQty = parseInt(select.dataset.originalQty) || 0;

        if (!productId) return;

        const availableStock = getAvailableStock(productId, originalQty);

        if (currentQty > availableStock) {
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
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 item-row';
    newRow.innerHTML = `
        <div class="col-md-5">
            <select name="products[]" class="form-control product-select" data-original-product="" data-original-qty="0" required>
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
        this.dataset.originalProduct = this.value;
        updateAllSelects();
        validateQuantities();
    });

    newQtyInput.addEventListener('input', validateQuantities);

    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        newRow.remove();
        updateAllSelects();
        validateQuantities();
    });

    updateAllSelects();
});

document.querySelectorAll('.product-select').forEach(select => {
    select.addEventListener('change', function() {
        this.dataset.originalProduct = this.value;
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
        validateQuantities();
    });
});

document.querySelector('form').addEventListener('submit', function(e) {
    if (!validateQuantities()) {
        e.preventDefault();
        alert('Qty tidak boleh melebihi stok!');
    }
});

updateAllSelects();
</script>
@endsection
