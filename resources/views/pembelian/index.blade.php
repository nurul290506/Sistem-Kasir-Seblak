@extends('layouts.app')

@section('page_title', 'Restok / Pembelian Barang')

@section('content')
<div class="card-custom">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0"><i class="fa-solid fa-cart-flatbed-suitcase text-primary me-2"></i> Riwayat Pembelian Stok</h5>
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary-custom btn-sm">
            <i class="fa-solid fa-plus me-1"></i> Catat Pembelian Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal Pembelian</th>
                    <th>Supplier</th>
                    <th>Admin Penginput</th>
                    <th>Total Biaya Pembelian</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $p)
                    <tr>
                        <td><strong>#{{ $p->id_pembelian }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_pembelian)->translatedFormat('d M Y') }}</td>
                        <td><div class="fw-bold">{{ $p->supplier->nama_supplier }}</div></td>
                        <td>{{ $p->user->nama_user }}</td>
                        <td><strong>Rp {{ number_format($p->total_pembelian, 0, ',', '.') }}</strong></td>
                        <td class="text-end">
                            <a href="{{ route('pembelian.show', $p->id_pembelian) }}" class="btn-action btn-action-view" title="Detail Rincian">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi pembelian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
