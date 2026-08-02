@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card-custom stat-card">
            <div class="stat-card-icon text-primary"><i class="fa-solid fa-rupiah-sign"></i></div>
            <div class="stat-card-label">Penjualan Hari Ini</div>
            <div class="stat-card-val">Rp {{ number_format($salesToday, 0, ',', '.') }}</div>
            <div class="text-muted mt-2" style="font-size: 13px;">
                <span class="text-success"><i class="fa-solid fa-calendar-day me-1"></i> Hari ini</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card-custom stat-card">
            <div class="stat-card-icon text-success"><i class="fa-solid fa-money-bill-trend-up"></i></div>
            <div class="stat-card-label">Penjualan Bulan Ini</div>
            <div class="stat-card-val">Rp {{ number_format($salesMonth, 0, ',', '.') }}</div>
            <div class="text-muted mt-2" style="font-size: 13px;">
                <span class="text-success"><i class="fa-solid fa-calendar-days me-1"></i> Bulan Berjalan</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card-custom stat-card">
            <div class="stat-card-icon text-warning"><i class="fa-solid fa-cart-shopping"></i></div>
            <div class="stat-card-label">Transaksi Hari Ini</div>
            <div class="stat-card-val">{{ $trxCountToday }} Trx</div>
            <div class="text-muted mt-2" style="font-size: 13px;">
                <span class="text-warning"><i class="fa-solid fa-people-carry-box me-1"></i> Dilayani</span>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card-custom stat-card">
            <div class="stat-card-icon text-danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="stat-card-label">Stok Menipis</div>
            <div class="stat-card-val">{{ $lowStockCount }} Item</div>
            <div class="text-muted mt-2" style="font-size: 13px;">
                <span class="text-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> Perlu Restok</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Column -->
    <div class="col-lg-8 mb-4">
        <div class="card-custom h-100">
            <div class="card-title-custom">
                <i class="fa-solid fa-chart-area"></i> Grafik Tren Penjualan (7 Hari Terakhir)
            </div>
            <div style="position: relative; height: 320px;">
                <canvas id="salesTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Alert / Low Stock Items -->
    <div class="col-lg-4 mb-4">
        <div class="card-custom h-100">
            <div class="card-title-custom text-danger">
                <i class="fa-solid fa-circle-exclamation"></i> Peringatan Stok Rendah
            </div>
            @if($lowStockItems->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-regular fa-face-smile d-block fs-1 mb-2 text-success"></i>
                    Stok barang dalam kondisi aman.
                </div>
            @else
                <div class="table-responsive border-0">
                    <table class="table align-middle m-0" style="font-size: 14px;">
                        <thead>
                            <tr class="text-muted" style="border-bottom: 1px solid #eee;">
                                <th class="pb-2 fw-bold">Barang</th>
                                <th class="pb-2 text-center fw-bold">Stok</th>
                                @if(auth()->user()->role === 'admin')
                                    <th class="pb-2 text-end fw-bold">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockItems as $item)
                                <tr style="border-bottom: 1px dashed #eee;">
                                    <td class="py-2">
                                        <div class="fw-bold text-dark">{{ $item->nama_barang }}</div>
                                        <small class="text-muted">{{ $item->satuan }}</small>
                                    </td>
                                    <td class="py-2 text-center">
                                        <span class="badge bg-danger rounded-pill px-2 py-1">{{ $item->stok }}</span>
                                    </td>
                                    @if(auth()->user()->role === 'admin')
                                        <td class="py-2 text-end">
                                            <a href="{{ route('pembelian.create') }}?id_barang={{ $item->id_barang }}" class="btn btn-sm btn-outline-danger" title="Restok">
                                                <i class="fa-solid fa-plus"></i> Restok
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Transactions Table -->
    <div class="col-12 mb-4">
        <div class="card-custom">
            <div class="card-title-custom">
                <i class="fa-solid fa-clock-rotate-left"></i> Transaksi Penjualan Terbaru
            </div>
            @if($recentTransactions->isEmpty())
                <div class="text-center py-5 text-muted">
                    Belum ada transaksi hari ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal & Waktu</th>
                                <th>Kasir</th>
                                <th>Metode Pembayaran</th>
                                <th>Total Belanja</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $tx)
                                <tr>
                                    <td><strong>#{{ $tx->id_transaksi }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($tx->tanggal_transaksi)->translatedFormat('d M Y') }} {{ $tx->jam_transaksi }}</td>
                                    <td>{{ $tx->user->nama_user }}</td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase px-2 py-1" style="font-size: 11px;">
                                            {{ $tx->metode_pembayaran }}
                                        </span>
                                    </td>
                                    <td><strong>Rp {{ number_format($tx->total_harga, 0, ',', '.') }}</strong></td>
                                    <td><span class="badge badge-custom badge-active">Selesai</span></td>
                                    <td class="text-end">
                                        <a href="{{ route('transaksi.show', $tx->id_transaksi) }}" class="btn-action btn-action-view" title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('transaksi.print', $tx->id_transaksi) }}" target="_blank" class="btn-action btn-action-edit" title="Cetak Struk">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesTrendsChart').getContext('2d');
        
        // Prepare chart data from Laravel PHP array
        const rawData = @json($chartData);
        
        const labels = rawData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        const totals = rawData.map(item => item.total);

        // Fallback if empty data
        if(labels.length === 0) {
            labels.push("Hari ini");
            totals.push(0);
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan Harian (Rp)',
                    data: totals,
                    borderColor: '#f24e1e',
                    backgroundColor: 'rgba(242, 78, 30, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#f24e1e',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
