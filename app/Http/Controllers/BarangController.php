<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\KategoriBarang;

class BarangController extends Controller
{
    public function index()
    {
        $items = Barang::with('kategori')->orderBy('id_barang', 'desc')->get();
        $categories = KategoriBarang::all();
        return view('barang.index', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori_barang,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori_barang,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $item = Barang::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diubah.');
    }

    public function destroy($id)
    {
        $item = Barang::findOrFail($id);

        if ($item->detailTransaksi()->count() > 0 || $item->detailPembelian()->count() > 0) {
            // Safe disable rather than deleting if it has transactions
            $item->update(['status' => 'nonaktif']);
            return redirect()->route('barang.index')->with('success', 'Barang memiliki riwayat transaksi, status diubah menjadi nonaktif.');
        }

        $item->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
