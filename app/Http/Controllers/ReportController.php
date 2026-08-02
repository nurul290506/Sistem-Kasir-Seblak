<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pembelian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily'); // daily, monthly, yearly
        $date = $request->get('date', Carbon::today()->toDateString());
        $month = $request->get('month', Carbon::today()->format('Y-m'));
        $year = $request->get('year', Carbon::today()->year);

        $salesQuery = Transaksi::query();
        $purchaseQuery = Pembelian::query();

        if ($period === 'daily') {
            $salesQuery->whereDate('tanggal_transaksi', $date);
            $purchaseQuery->whereDate('tanggal_pembelian', $date);
            
            // Detail listing
            $transactions = Transaksi::with(['user', 'detailTransaksi.barang'])
                ->whereDate('tanggal_transaksi', $date)
                ->orderBy('jam_transaksi', 'asc')
                ->get();
            
            $purchases = Pembelian::with(['supplier', 'user'])
                ->whereDate('tanggal_pembelian', $date)
                ->orderBy('created_at', 'asc')
                ->get();
            
            $groupTitle = 'Harian (' . Carbon::parse($date)->translatedFormat('d F Y') . ')';

        } elseif ($period === 'monthly') {
            $parsedMonth = Carbon::parse($month . '-01');
            $salesQuery->whereYear('tanggal_transaksi', $parsedMonth->year)
                       ->whereMonth('tanggal_transaksi', $parsedMonth->month);
            $purchaseQuery->whereYear('tanggal_pembelian', $parsedMonth->year)
                          ->whereMonth('tanggal_pembelian', $parsedMonth->month);

            // Detail listing
            $transactions = Transaksi::with(['user'])
                ->whereYear('tanggal_transaksi', $parsedMonth->year)
                ->whereMonth('tanggal_transaksi', $parsedMonth->month)
                ->orderBy('tanggal_transaksi', 'asc')
                ->get();
            
            $purchases = Pembelian::with(['supplier', 'user'])
                ->whereYear('tanggal_pembelian', $parsedMonth->year)
                ->whereMonth('tanggal_pembelian', $parsedMonth->month)
                ->orderBy('tanggal_pembelian', 'asc')
                ->get();

            $groupTitle = 'Bulanan (' . $parsedMonth->translatedFormat('F Y') . ')';

        } else { // yearly
            $salesQuery->whereYear('tanggal_transaksi', $year);
            $purchaseQuery->whereYear('tanggal_pembelian', $year);

            // Detail listing
            $transactions = Transaksi::with(['user'])
                ->whereYear('tanggal_transaksi', $year)
                ->orderBy('tanggal_transaksi', 'asc')
                ->get();
            
            $purchases = Pembelian::with(['supplier', 'user'])
                ->whereYear('tanggal_pembelian', $year)
                ->orderBy('tanggal_pembelian', 'asc')
                ->get();

            $groupTitle = 'Tahunan (' . $year . ')';
        }

        // Totals
        $totalSales = $salesQuery->sum('total_harga');
        $trxCount = $salesQuery->count();
        $totalPurchases = $purchaseQuery->sum('total_pembelian');
        
        // Profit estimation (Revenue - Stock Purchases)
        $estimatedProfit = $totalSales - $totalPurchases;

        // Group data for chart helper
        $chartSales = [];
        if ($period === 'daily') {
            // Group by hour
            $hourlyData = Transaksi::select(
                DB::raw('HOUR(jam_transaksi) as hour'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereDate('tanggal_transaksi', $date)
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get();
            
            for ($h = 8; $h <= 22; $h++) {
                $match = $hourlyData->firstWhere('hour', $h);
                $chartSales[] = [
                    'label' => sprintf('%02d:00', $h),
                    'total' => $match ? (float)$match->total : 0
                ];
            }
        } elseif ($period === 'monthly') {
            // Group by day of month
            $parsedMonth = Carbon::parse($month . '-01');
            $daysInMonth = $parsedMonth->daysInMonth;
            
            $dailyData = Transaksi::select(
                DB::raw('DAY(tanggal_transaksi) as day'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_transaksi', $parsedMonth->year)
            ->whereMonth('tanggal_transaksi', $parsedMonth->month)
            ->groupBy('day')
            ->get();

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $match = $dailyData->firstWhere('day', $d);
                $chartSales[] = [
                    'label' => 'Tgl ' . $d,
                    'total' => $match ? (float)$match->total : 0
                ];
            }
        } else {
            // Group by month of year
            $monthlyData = Transaksi::select(
                DB::raw('MONTH(tanggal_transaksi) as month_num'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tanggal_transaksi', $year)
            ->groupBy('month_num')
            ->get();

            for ($m = 1; $m <= 12; $m++) {
                $match = $monthlyData->firstWhere('month_num', $m);
                $chartSales[] = [
                    'label' => Carbon::create()->month($m)->translatedFormat('M'),
                    'total' => $match ? (float)$match->total : 0
                ];
            }
        }

        return view('reports.index', compact(
            'period',
            'date',
            'month',
            'year',
            'transactions',
            'purchases',
            'totalSales',
            'trxCount',
            'totalPurchases',
            'estimatedProfit',
            'groupTitle',
            'chartSales'
        ));
    }
}
