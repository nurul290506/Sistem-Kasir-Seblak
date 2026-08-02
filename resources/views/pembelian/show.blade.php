@extends('layouts.app')

@section('page_title', 'Detail Rincian Pembelian')

@section('content')
<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0"><i class="fa-solid fa-file-invoice text-primary me-2"></i> Transaksi Pembelian #{{ $purchase->id_pembelian }}</h5>
        <div>
            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary-custom btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Metadata Section -->
    <div class="row bg-light p-4 rounded-3 mb-4 g-3">
        <div class="col-md-4">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Pemasok / Supplier</small>
            <div class="fw-bold fs-5 text-dark">{{ $purchase->supplier->nama_supplier }}</div>
            <div class="text-muted mt-1"><i class="fa-solid fa-phone me-1"></i> {{ $purchase->supplier->no_hp }}</div>
            <div class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ $purchase->supplier->alamat }}</div>
        </div>
        <div class="col-md-4">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Detail Waktu</small>
            <div class="fw-bold fs-5 text-dark">
                {{ \Carbon\Carbon::parse($purchase->tanggal_pembelian)->translatedFormat('d F Y') }}
            </div>
            <div class="text-muted mt-1"><i class="fa-regular fa-clock me-1"></i> Dicatat: {{ $purchase->created_at->format('H:i') }} WIB</div>
        </div>
        <div class="col-md-4">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Penginput</small>
            <div class="fw-bold fs-5 text-dark">{{ $purchase->user->nama_user }}</div>
            <div class="text-muted mt-1"><i class="fa-solid fa-shield-halved me-1"></i> Role: {{ ucfirst($purchase->user->role) }}</div>
        </div>
    </div>

    <!-- Item Breakdown Table -->
    <h6 class="fw-bold mb-3"><i class="fa-solid fa-rectangle-list text-primary me-2"></i> Rincian Barang Masuk</h6>
    <div class="table-responsive mb-4">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Satuan</th>
                    <th class="text-center">Jumlah (Qty)</th>
                    <th class="text-end">Harga Beli / Unit</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->detailPembelian as $detail)
                    <tr>
                        <td><strong>{{ $detail->barang->nama_barang }}</strong></td>
                        <td><code>{{ $detail->barang->satuan }}</code></td>
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-end"><strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <td colspan="4" class="text-end fw-bold">Total Pembelian:</td>
                    <td class="text-end text-primary fs-5 fw-bold">Rp {{ number_format($purchase->total_pembelian, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
