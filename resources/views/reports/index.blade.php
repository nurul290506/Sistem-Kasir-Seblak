@extends('layouts.app')

@section('page_title', 'Laporan & Analisis Penjualan')

@section('content')
<div class="card-custom mb-4">
    <h5 class="mb-3"><i class="fa-solid fa-filter text-primary me-2"></i> Filter Periode Laporan</h5>
    <form action="{{ route('reports.index') }}" method="GET">
        <div class="row align-items-end g-3">
            <div class="col-md-3">
                <label for="period" class="form-label fw-bold small text-muted">Periode</label>
                <select name="period" id="period" class="form-select">
                    <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            
            <div class="col-md-4 period-input" id="input-daily" style="display: {{ $period === 'daily' ? 'block' : 'none' }};">
                <label for="date" class="form-label fw-bold small text-muted">Pilih Tanggal</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ $date }}">
            </div>

            <div class="col-md-4 period-input" id="input-monthly" style="display: {{ $period === 'monthly' ? 'block' : 'none' }};">
                <label for="month" class="form-label fw-bold small text-muted">Pilih Bulan</label>
                <input type="month" name="month" id="month" class="form-control" value="{{ $month }}">
            </div>

            <div class="col-md-4 period-input" id="input-yearly" style="display: {{ $period === 'yearly' ? 'block' : 'none' }};">
                <label for="year" class="form-label fw-bold small text-muted">Pilih Tahun</label>
                <select name="year" id="year" class="form-select">
                    @for($y = \Carbon\Carbon::today()->year; $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary-custom w-100 py-2">
                    <i class="fa-solid fa-arrows-rotate me-1"></i> Tampilkan Laporan
                </button>
            </div>
        </div>
    </form>
</div>

<h4 class="mb-4 text-secondary">
    <i class="fa-solid fa-chart-line text-primary me-2"></i> Laporan {{ $groupTitle }}
</h4>

<div class="row">
    <!-- Stat Summary Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card-custom stat-card">
            <div class="stat-card-icon text-success opacity-25"><i class="fa-solid fa-rupiah-sign"></i></div>
            <div class="stat-card-label">Total Pendapatan</div>
            <div class="stat-card-val text-success">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="text-muted mt-2" style="font-size: 13px;">
                <i class="fa-solid fa-cash-register me-1"></i> Dari {{ $trxCount }} Transaksi
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card-custom stat-card">
            <div class="stat-card-icon text-danger opacity-25"><i class="fa-solid fa-cart-shopping"></i></div>
            <div class="stat-card-label">Total Pembelian Stok</div>
            <div class="stat-card-val text-danger">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</div>
            <div class="text-muted mt-2" style="font-size: 13px;">
                <i class="fa-solid fa-truck-ramp-box me-1"></i> Pengeluaran Supplier
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card-custom stat-card">
            <div class="stat-card-icon text-primary opacity-25"><i class="fa-solid fa-scale-balanced"></i></div>
            <div class="stat-card-label">Estimasi Laba Bersih</div>
            <div class="stat-card-val text-primary">Rp {{ number_format($estimatedProfit, 0, ',', '.') }}</div>
            <div class="text-muted mt-2" style="font-size: 13px;">
                <i class="fa-solid fa-sack-dollar me-1"></i> Pendapatan - Pembelian
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card-custom stat-card">
            <div class="stat-card-icon text-secondary opacity-25"><i class="fa-solid fa-users"></i></div>
            <div class="stat-card-label">Volume Transaksi</div>
            <div class="stat-card-val">{{ $trxCount }} Trx</div>
            <div class="text-muted mt-2" style="font-size: 13px;">
                <span class="text-success fw-bold"><i class="fa-solid fa-check me-1"></i> Selesai Dilayani</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Column -->
    <div class="col-12 mb-4">
        <div class="card-custom">
            <div class="card-title-custom">
                <i class="fa-solid fa-chart-column"></i> Visualisasi Penjualan
            </div>
            <div style="position: relative; height: 350px;">
                <canvas id="reportChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sales List -->
    <div class="col-lg-6 mb-4">
        <div class="card-custom h-100">
            <div class="card-title-custom">
                <i class="fa-solid fa-receipt text-success"></i> Transaksi Penjualan
            </div>
            @if($transactions->isEmpty())
                <div class="text-center py-5 text-muted">
                    Tidak ada transaksi penjualan pada periode ini.
                </div>
            @else
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-custom table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $t)
                                <tr>
                                    <td><strong>#{{ $t->id_transaksi }}</strong></td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($t->tanggal_transaksi)->translatedFormat('d M Y') }}
                                        <small class="text-muted d-block">{{ $t->jam_transaksi }}</small>
                                    </td>
                                    <td>{{ $t->user->nama_user }}</td>
                                    <td class="text-end"><strong>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Purchase List -->
    <div class="col-lg-6 mb-4">
        <div class="card-custom h-100">
            <div class="card-title-custom">
                <i class="fa-solid fa-truck-ramp-box text-danger"></i> Pembelian & Restok Barang
            </div>
            @if($purchases->isEmpty())
                <div class="text-center py-5 text-muted">
                    Tidak ada transaksi pembelian pada periode ini.
                </div>
            @else
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-custom table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $p)
                                <tr>
                                    <td><strong>#{{ $p->id_pembelian }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pembelian)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $p->supplier->nama_supplier }}</td>
                                    <td class="text-end"><strong>Rp {{ number_format($p->total_pembelian, 0, ',', '.') }}</strong></td>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const periodSelect = document.getElementById('period');
        const periodInputs = document.querySelectorAll('.period-input');

        periodSelect.addEventListener('change', function() {
            // Hide all inputs
            periodInputs.forEach(el => el.style.display = 'none');
            // Show selected period input
            document.getElementById(`input-${this.value}`).style.display = 'block';
        });

        // Chart render
        const ctx = document.getElementById('reportChart').getContext('2d');
        const chartData = @json($chartSales);
        
        const labels = chartData.map(item => item.label);
        const totals = chartData.map(item => item.total);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan Penjualan (Rp)',
                    data: totals,
                    backgroundColor: 'rgba(230, 57, 70, 0.75)',
                    hoverBackgroundColor: '#E63946',
                    borderRadius: 6,
                    borderWidth: 0
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
