<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function index()
    {
        $purchases = Pembelian::with(['supplier', 'user'])->orderBy('id_pembelian', 'desc')->get();
        return view('pembelian.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $items = Barang::where('status', 'aktif')->get();
        return view('pembelian.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'tanggal_pembelian' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barang,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $totalPembelian = 0;

            // 1. Create the Pembelian record
            $pembelian = Pembelian::create([
                'id_supplier' => $request->id_supplier,
                'id_user' => Auth::id(),
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'total_pembelian' => 0, // Update later after calculating details
            ]);

            // 2. Loop details
            foreach ($request->items as $itemData) {
                $subtotal = $itemData['jumlah'] * $itemData['harga_beli'];
                $totalPembelian += $subtotal;

                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_barang' => $itemData['id_barang'],
                    'jumlah' => $itemData['jumlah'],
                    'harga_beli' => $itemData['harga_beli'],
                    'subtotal' => $subtotal,
                ]);

                // 3. Update Barang stock
                $barang = Barang::findOrFail($itemData['id_barang']);
                $barang->stok += $itemData['jumlah'];
                $barang->save();
            }

            // 4. Update the total on the Pembelian record
            $pembelian->update([
                'total_pembelian' => $totalPembelian,
            ]);

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian barang berhasil dicatat dan stok telah bertambah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $purchase = Pembelian::with(['supplier', 'user', 'detailPembelian.barang'])->findOrFail($id);
        return view('pembelian.show', compact('purchase'));
    }
}
