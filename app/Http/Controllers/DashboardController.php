<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::today()->startOfMonth();

        // 1. Total Penjualan Hari Ini
        $salesToday = Transaksi::whereDate('tanggal_transaksi', $today)->sum('total_harga');

        // 2. Total Penjualan Bulan Ini
        $salesMonth = Transaksi::whereDate('tanggal_transaksi', '>=', $startOfMonth)->sum('total_harga');

        // 3. Jumlah Transaksi Hari Ini
        $trxCountToday = Transaksi::whereDate('tanggal_transaksi', $today)->count();

        // 4. Jumlah Jenis Barang
        $totalBarang = Barang::count();

        // 5. Barang Stok Menipis (< 15)
        $lowStockCount = Barang::where('stok', '<', 15)->count();
        $lowStockItems = Barang::where('stok', '<', 15)->orderBy('stok', 'asc')->get();

        // 6. Transaksi Terbaru
        $recentTransactions = Transaksi::with('user')
            ->orderBy('id_transaksi', 'desc')
            ->limit(5)
            ->get();

        // 7. Grafik Penjualan 7 Hari Terakhir
        $chartData = Transaksi::select(
                DB::raw('DATE(tanggal_transaksi) as date'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereDate('tanggal_transaksi', '>=', Carbon::today()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('dashboard', compact(
            'salesToday',
            'salesMonth',
            'trxCountToday',
            'totalBarang',
            'lowStockCount',
            'lowStockItems',
            'recentTransactions',
            'chartData'
        ));
    }
}
