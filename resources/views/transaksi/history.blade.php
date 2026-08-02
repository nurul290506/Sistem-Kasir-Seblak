@extends('layouts.app')

@section('page_title', 'Riwayat Transaksi Penjualan')

@section('content')
<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0"><i class="fa-solid fa-receipt text-primary me-2"></i> Log Transaksi</h5>
        <a href="{{ route('transaksi.index') }}" class="btn btn-primary-custom btn-sm">
            <i class="fa-solid fa-cash-register me-1"></i> Buka Kasir
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal & Jam</th>
                    <th>Kasir</th>
                    <th>Metode Pembayaran</th>
                    <th>Total Harga</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                    <tr>
                        <td><strong>#{{ $t->id_transaksi }}</strong></td>
                        <td>
                            <div>{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') }}</div>
                            <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> {{ $t->jam_transaksi }}</small>
                        </td>
                        <td>{{ $t->user->nama_user }}</td>
                        <td>
                            <span class="badge bg-light text-dark border text-uppercase px-2 py-1" style="font-size: 11px;">
                                {{ $t->metode_pembayaran }}
                            </span>
                        </td>
                        <td><strong>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</strong></td>
                        <td>Rp {{ number_format($t->bayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($t->kembalian, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <a href="{{ route('transaksi.show', $t->id_transaksi) }}" class="btn-action btn-action-view me-1" title="Detail Rincian">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('transaksi.print', $t->id_transaksi) }}" target="_blank" class="btn-action btn-action-edit" title="Cetak Struk">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat transaksi penjualan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
