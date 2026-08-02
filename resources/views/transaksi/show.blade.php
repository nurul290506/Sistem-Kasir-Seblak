@extends('layouts.app')

@section('page_title', 'Detail Transaksi Penjualan')

@section('content')
<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i> Rincian Transaksi #{{ $transaction->id_transaksi }}</h5>
        <div>
            <a href="{{ route('transaksi.print', $transaction->id_transaksi) }}" target="_blank" class="btn btn-primary-custom btn-sm me-2">
                <i class="fa-solid fa-print me-1"></i> Cetak Struk
            </a>
            <a href="{{ route('transaksi.history') }}" class="btn btn-secondary-custom btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Metadata Section -->
    <div class="row bg-light p-4 rounded-3 mb-4 g-3">
        <div class="col-md-3">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Kasir</small>
            <div class="fw-bold fs-5 text-dark">{{ $transaction->user->nama_user }}</div>
            <div class="text-muted mt-1"><i class="fa-solid fa-user me-1"></i> {{ ucfirst($transaction->user->role) }}</div>
        </div>
        <div class="col-md-3">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Waktu Transaksi</small>
            <div class="fw-bold fs-5 text-dark">
                {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->translatedFormat('d F Y') }}
            </div>
            <div class="text-muted mt-1"><i class="fa-regular fa-clock me-1"></i> Jam: {{ $transaction->jam_transaksi }} WIB</div>
        </div>
        <div class="col-md-3">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Metode Pembayaran</small>
            <div class="fw-bold fs-5 text-dark text-uppercase">
                {{ $transaction->metode_pembayaran }}
            </div>
            <div class="text-muted mt-1">
                @if($transaction->metode_pembayaran == 'tunai')
                    <i class="fa-solid fa-money-bill-wave me-1"></i> Uang Tunai
                @elseif($transaction->metode_pembayaran == 'transfer')
                    <i class="fa-solid fa-building-columns me-1"></i> Transfer Bank
                @else
                    <i class="fa-solid fa-qrcode me-1"></i> Kode QRIS
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <small class="text-muted text-uppercase fw-bold d-block mb-1">Status Pembayaran</small>
            <div class="fw-bold fs-5 text-success">
                <i class="fa-solid fa-circle-check me-1"></i> LUNAS
            </div>
        </div>
    </div>

    <!-- Table Breakdown -->
    <h6 class="fw-bold mb-3"><i class="fa-solid fa-list text-primary me-2"></i> Daftar Belanjaan Pelanggan</h6>
    <div class="table-responsive mb-4">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Item Menu / Topping</th>
                    <th>Tingkat Pedas</th>
                    <th class="text-center">Kuantitas (Qty)</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->detailTransaksi as $detail)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $detail->barang->nama_barang }}</div>
                            <small class="text-muted">Kategori: {{ $detail->barang->kategori->nama_kategori }}</small>
                        </td>
                        <td>
                            @if(!is_null($detail->level_pedas))
                                <span class="badge bg-danger rounded px-2 py-1" style="font-size:11px;">
                                    Lvl {{ $detail->level_pedas }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $detail->jumlah }} {{ $detail->barang->satuan }}</td>
                        <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-end"><strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold pt-3" style="border: none;">Total Harga:</td>
                    <td class="text-end fs-5 fw-bold pt-3" style="border: none;">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-bold" style="border: none;">Jumlah Bayar:</td>
                    <td class="text-end fs-5 text-dark" style="border: none;">Rp {{ number_format($transaction->bayar, 0, ',', '.') }}</td>
                </tr>
                <tr class="table-light">
                    <td colspan="4" class="text-end fw-bold text-success fs-5">Kembalian:</td>
                    <td class="text-end text-success fs-5 fw-bold">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
