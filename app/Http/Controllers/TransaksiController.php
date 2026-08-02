<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index()
    {
        // Cashier page (POS interface)
        $items = Barang::with('kategori')
            ->where('status', 'aktif')
            ->orderBy('id_kategori', 'asc')
            ->orderBy('nama_barang', 'asc')
            ->get();
        
        return view('transaksi.index', compact('items'));
    }

    public function history()
    {
        // Sales transaction history
        $transactions = Transaksi::with(['user', 'detailTransaksi.barang'])
            ->orderBy('id_transaksi', 'desc')
            ->get();
        return view('transaksi.history', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:tunai,transfer,qris',
            'bayar' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.level_pedas' => 'nullable|integer|min:0|max:5',
        ]);

        if ($request->bayar < $request->total_harga) {
            return response()->json(['error' => 'Jumlah bayar kurang dari total harga.'], 422);
        }

        DB::beginTransaction();
        try {
            $now = Carbon::now();
            $kembalian = $request->bayar - $request->total_harga;

            // 1. Create Transaksi record
            $transaksi = Transaksi::create([
                'id_user' => Auth::id(),
                'tanggal_transaksi' => $now->toDateString(),
                'jam_transaksi' => $now->toTimeString(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_harga' => $request->total_harga,
                'bayar' => $request->bayar,
                'kembalian' => $kembalian,
            ]);

            // 2. Add details & reduce stock
            foreach ($request->items as $itemData) {
                $barang = Barang::findOrFail($itemData['id_barang']);
                
                // Check stock availability
                if ($barang->stok < $itemData['jumlah']) {
                    throw new \Exception("Stok untuk {$barang->nama_barang} tidak mencukupi (Tersedia: {$barang->stok}).");
                }

                $subtotal = $itemData['jumlah'] * $barang->harga_jual;

                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_barang' => $barang->id_barang,
                    'jumlah' => $itemData['jumlah'],
                    'harga' => $barang->harga_jual,
                    'subtotal' => $subtotal,
                    'level_pedas' => $itemData['level_pedas'] ?? null,
                ]);

                // Reduce stock
                $barang->stok -= $itemData['jumlah'];
                $barang->save();
            }

            DB::commit();

            return response()->json([
                'success' => 'Transaksi berhasil disimpan.',
                'id_transaksi' => $transaksi->id_transaksi,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $transaction = Transaksi::with(['user', 'detailTransaksi.barang'])->findOrFail($id);
        return view('transaksi.show', compact('transaction'));
    }

    public function printReceipt($id)
    {
        $transaction = Transaksi::with(['user', 'detailTransaksi.barang'])->findOrFail($id);
        return view('transaksi.print', compact('transaction'));
    }
}
