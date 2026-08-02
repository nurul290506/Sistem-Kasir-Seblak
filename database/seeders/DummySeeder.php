<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DummySeeder extends Seeder
{
    public function run()
    {
        // Seed Barang
        $toppings = ['Sosis Sapi', 'Sosis Ayam', 'Bakso Ikan', 'Bakso Sapi', 'Crab Stick', 'Chikuwa', 'Dumpling Keju', 'Dumpling Ayam', 'Fish Roll', 'Kikil', 'Kerupuk Seblak', 'Makaroni', 'Mie Kuning', 'Kwetiau', 'Tulangan', 'Ceker', 'Sayur Sawi', 'Telur Puyuh', 'Telur Ayam', 'Batagor', 'Siomay Kering'];
        $minuman = ['Es Teh Manis', 'Es Jeruk', 'Es Milo', 'Nutrisari', 'Good Day', 'Teh Tarik', 'Kopi Susu', 'Lemon Tea', 'Air Mineral', 'Es Kopi Hitam'];

        foreach($toppings as $top) {
            if(!Barang::where('nama_barang', $top)->exists()) {
                Barang::create([
                    'id_kategori' => 1,
                    'nama_barang' => $top,
                    'harga_jual' => rand(3, 5) * 1000,
                    'stok' => rand(20, 100),
                    'satuan' => 'Pcs',
                    'status' => 'aktif'
                ]);
            }
        }
        foreach($minuman as $min) {
            if(!Barang::where('nama_barang', $min)->exists()) {
                Barang::create([
                    'id_kategori' => 2,
                    'nama_barang' => $min,
                    'harga_jual' => rand(4, 8) * 1000,
                    'stok' => rand(20, 100),
                    'satuan' => 'Cup',
                    'status' => 'aktif'
                ]);
            }
        }

        $barangs = Barang::all();
        $barangIds = $barangs->pluck('id_barang')->toArray();

        if (empty($barangIds)) {
            echo "No barang available.";
            return;
        }

        // Seed Transaksi
        DB::beginTransaction();
        try {
            for($i=1; $i<=30; $i++) {
                $total = 0;
                $items = rand(2, 5);
                $transItems = [];
                for($j=0; $j<$items; $j++) {
                    $b_id = $barangIds[array_rand($barangIds)];
                    $b = $barangs->where('id_barang', $b_id)->first();
                    $qty = rand(1, 3);
                    $sub = $qty * $b->harga_jual;
                    $total += $sub;
                    $transItems[] = [
                        'id_barang' => $b_id,
                        'jumlah' => $qty,
                        'harga' => $b->harga_jual,
                        'subtotal' => $sub,
                        'level_pedas' => rand(0, 5)
                    ];
                }
                $t = Transaksi::create([
                    'id_user' => 1, // Assuming kasir id_user = 1
                    'tanggal_transaksi' => Carbon::now()->subDays(rand(1,30))->toDateString(),
                    'jam_transaksi' => Carbon::now()->subMinutes(rand(10, 1440))->toTimeString(),
                    'metode_pembayaran' => ['tunai', 'transfer', 'qris'][rand(0, 2)],
                    'total_harga' => $total,
                    'bayar' => ceil($total / 10000) * 10000,
                    'kembalian' => (ceil($total / 10000) * 10000) - $total
                ]);
                foreach($transItems as $ti) {
                    DetailTransaksi::create(array_merge($ti, ['id_transaksi' => $t->id_transaksi]));
                }
            }
            DB::commit();
            echo "Transaksi done.\n";
        } catch (\Exception $e) {
            DB::rollback();
            echo "Error Transaksi: " . $e->getMessage() . "\n";
        }

        // Seed Pembelian
        DB::beginTransaction();
        try {
            for($i=1; $i<=30; $i++) {
                $total = 0;
                $items = rand(2, 4);
                $pemItems = [];
                for($j=0; $j<$items; $j++) {
                    $b_id = $barangIds[array_rand($barangIds)];
                    $b = $barangs->where('id_barang', $b_id)->first();
                    $qty = rand(10, 50);
                    $h_beli = $b->harga_jual * 0.6;
                    $sub = $qty * $h_beli;
                    $total += $sub;
                    $pemItems[] = [
                        'id_barang' => $b_id,
                        'jumlah' => $qty,
                        'harga_beli' => $h_beli,
                        'subtotal' => $sub
                    ];
                }
                $p = Pembelian::create([
                    'id_supplier' => rand(1, 2),
                    'id_user' => 1,
                    'tanggal_pembelian' => Carbon::now()->subDays(rand(1,30))->toDateString(),
                    'total_pembelian' => $total
                ]);
                foreach($pemItems as $pi) {
                    DetailPembelian::create(array_merge($pi, ['id_pembelian' => $p->id_pembelian]));
                }
            }
            DB::commit();
            echo "Pembelian done.\n";
        } catch (\Exception $e) {
            DB::rollback();
            echo "Error Pembelian: " . $e->getMessage() . "\n";
        }
    }
}
