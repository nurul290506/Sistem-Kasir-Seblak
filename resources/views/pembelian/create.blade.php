@extends('layouts.app')

@section('page_title', 'Catat Pembelian Stok Baru')

@section('content')
<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0"><i class="fa-solid fa-cart-plus text-primary me-2"></i> Form Restok Barang</h5>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary-custom btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <label for="id_supplier" class="form-label fw-bold">Pilih Supplier / Pemasok</label>
                <select name="id_supplier" id="id_supplier" class="form-select" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id_supplier }}" {{ old('id_supplier') == $sup->id_supplier ? 'selected' : '' }}>
                            {{ $sup->nama_supplier }} ({{ $sup->alamat }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="tanggal_pembelian" class="form-label fw-bold">Tanggal Pembelian</label>
                <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" class="form-control" 
                       value="{{ old('tanggal_pembelian', \Carbon\Carbon::today()->toDateString()) }}" required>
            </div>
        </div>

        <div class="border-top pt-4">
            <h6 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-list-check me-2"></i> Rincian Barang yang Dibeli</h6>
            
            <div class="table-responsive border-0">
                <table class="table align-middle" id="purchase-table">
                    <thead>
                        <tr class="table-light">
                            <th style="width: 40%;">Nama Barang / Menu</th>
                            <th style="width: 20%;">Jumlah (Qty)</th>
                            <th style="width: 20%;">Harga Beli / Unit (Rp)</th>
                            <th style="width: 15%;">Subtotal (Rp)</th>
                            <th style="width: 5%;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="purchase-rows">
                        <!-- Rows injected via JS -->
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm mb-4" id="add-row-btn">
                <i class="fa-solid fa-plus me-1"></i> Tambah Item
            </button>

            <!-- Grand Total Section -->
            <div class="d-flex justify-content-end align-items-center gap-3 p-3 bg-light rounded-3 mb-4">
                <span class="fw-bold text-muted">Total Pembelian:</span>
                <span class="fs-4 fw-extrabold text-primary" id="grand-total-display">Rp 0</span>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary-custom">Batal</a>
            <button type="submit" class="btn btn-primary-custom">Simpan & Masuk Stok</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const items = @json($items);
        const rowsContainer = document.getElementById('purchase-rows');
        const addRowBtn = document.getElementById('add-row-btn');
        const grandTotalDisplay = document.getElementById('grand-total-display');
        let rowIndex = 0;

        // Function to create a new row
        function addRow(selectedBarangId = '') {
            const tr = document.createElement('tr');
            tr.id = `row-${rowIndex}`;

            let optionsHtml = '<option value="">-- Pilih Barang --</option>';
            items.forEach(item => {
                const isSelected = item.id_barang == selectedBarangId ? 'selected' : '';
                optionsHtml += `<option value="${item.id_barang}" ${isSelected} data-satuan="${item.satuan}">${item.nama_barang} (${item.satuan})</option>`;
            });

            tr.innerHTML = `
                <td>
                    <select name="items[${rowIndex}][id_barang]" class="form-select barang-select" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" name="items[${rowIndex}][jumlah]" class="form-control qty-input" min="1" value="10" required>
                        <span class="input-group-text unit-label text-muted" style="font-size:12px;">unit</span>
                    </div>
                </td>
                <td>
                    <input type="number" name="items[${rowIndex}][harga_beli]" class="form-control price-input" min="0" value="1000" required>
                </td>
                <td>
                    <span class="fw-bold text-dark subtotal-display" id="subtotal-${rowIndex}">Rp 10.000</span>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" data-row-id="row-${rowIndex}">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            `;

            rowsContainer.appendChild(tr);
            rowIndex++;
            
            // Re-bind listeners
            bindRowListeners(tr);
            calculateGrandTotal();
        }

        function bindRowListeners(row) {
            const barangSelect = row.querySelector('.barang-select');
            const qtyInput = row.querySelector('.qty-input');
            const priceInput = row.querySelector('.price-input');
            const unitLabel = row.querySelector('.unit-label');
            const removeBtn = row.querySelector('.remove-row-btn');

            barangSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const satuan = selectedOption.getAttribute('data-satuan');
                unitLabel.innerText = satuan ? satuan : 'unit';
                calculateRowSubtotal(row);
            });

            qtyInput.addEventListener('input', () => calculateRowSubtotal(row));
            priceInput.addEventListener('input', () => calculateRowSubtotal(row));

            removeBtn.addEventListener('click', function() {
                const rowId = this.getAttribute('data-row-id');
                document.getElementById(rowId).remove();
                calculateGrandTotal();
            });
        }

        function calculateRowSubtotal(row) {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const subtotal = qty * price;
            row.querySelector('.subtotal-display').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            const rows = rowsContainer.querySelectorAll('tr');
            rows.forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                grandTotal += qty * price;
            });
            grandTotalDisplay.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        // Add initial row or check if URL param exists
        const urlParams = new URLSearchParams(window.location.search);
        const preselectedBarangId = urlParams.get('id_barang');
        
        addRow(preselectedBarangId || '');

        addRowBtn.addEventListener('click', () => addRow());
    });
</script>
@endsection
