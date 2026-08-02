<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\KategoriBarang;

class BannerSeeder extends Seeder
{
    public function run()
    {
        // Ensure Kategori 3 (Cemilan) exists
        KategoriBarang::firstOrCreate(
            ['id_kategori' => 3],
            ['nama_kategori' => 'Cemilan']
        );

        $items = [
            // Toppings / Seblak Items (Kategori 1)
            ['nama_barang' => 'Jamur Enoki', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Jamur Kuping', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Dumpling', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Bakso Ayam Kecil', 'id_kategori' => 1, 'harga_jual' => 1000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Bakso Ayam Sedang', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Bakso Jamur', 'id_kategori' => 1, 'harga_jual' => 5000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Bakso Cabai', 'id_kategori' => 1, 'harga_jual' => 5000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Bakso Tahu', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Bakso Salmon', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Mie Tiaw', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Porsi'],
            ['nama_barang' => 'Mie Telur', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Porsi'],
            
            ['nama_barang' => 'Mie Hun', 'id_kategori' => 1, 'harga_jual' => 3000, 'satuan' => 'Porsi'],
            ['nama_barang' => 'Indomie', 'id_kategori' => 1, 'harga_jual' => 5000, 'satuan' => 'Bungkus'],
            ['nama_barang' => 'Intermie', 'id_kategori' => 1, 'harga_jual' => 3000, 'satuan' => 'Bungkus'],
            ['nama_barang' => 'Eko Mie', 'id_kategori' => 1, 'harga_jual' => 3000, 'satuan' => 'Bungkus'],
            ['nama_barang' => 'Cuanki Lidah', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Batagor Mini', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => '5 Pcs'],
            ['nama_barang' => 'Pangsit', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Pangsit Goreng', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],
            ['nama_barang' => 'Pilus', 'id_kategori' => 1, 'harga_jual' => 1000, 'satuan' => 'Bungkus'],
            ['nama_barang' => 'Kerupuk', 'id_kategori' => 1, 'harga_jual' => 3000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Telur', 'id_kategori' => 1, 'harga_jual' => 3000, 'satuan' => 'Butir'],
            ['nama_barang' => 'Sayur', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Porsi'],
            ['nama_barang' => 'Ceker', 'id_kategori' => 1, 'harga_jual' => 2000, 'satuan' => 'Pcs'],

            // Minuman (Kategori 2)
            ['nama_barang' => 'Es Teh', 'id_kategori' => 2, 'harga_jual' => 5000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Nutrisari', 'id_kategori' => 2, 'harga_jual' => 5000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Milo', 'id_kategori' => 2, 'harga_jual' => 6000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Teh Tarik', 'id_kategori' => 2, 'harga_jual' => 7000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Capucino', 'id_kategori' => 2, 'harga_jual' => 7000, 'satuan' => 'Cup'],
            ['nama_barang' => 'White Coffe', 'id_kategori' => 2, 'harga_jual' => 5000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Lemon Tea', 'id_kategori' => 2, 'harga_jual' => 5000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Teh Hijau', 'id_kategori' => 2, 'harga_jual' => 5000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Sirup Kurnia', 'id_kategori' => 2, 'harga_jual' => 5000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Sirup Melon', 'id_kategori' => 2, 'harga_jual' => 5000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Sirup Jeruk', 'id_kategori' => 2, 'harga_jual' => 5000, 'satuan' => 'Cup'],

            ['nama_barang' => 'Capucino Blend', 'id_kategori' => 2, 'harga_jual' => 10000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Coklat Blend', 'id_kategori' => 2, 'harga_jual' => 10000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Taro Blend', 'id_kategori' => 2, 'harga_jual' => 10000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Teh Tarik Blend', 'id_kategori' => 2, 'harga_jual' => 10000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Milo Blend', 'id_kategori' => 2, 'harga_jual' => 10000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Green Tea Susu', 'id_kategori' => 2, 'harga_jual' => 10000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Teh Tarik Susu', 'id_kategori' => 2, 'harga_jual' => 10000, 'satuan' => 'Cup'],

            ['nama_barang' => 'Stroberi Squash', 'id_kategori' => 2, 'harga_jual' => 12000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Lemon Squash', 'id_kategori' => 2, 'harga_jual' => 12000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Melon Squash', 'id_kategori' => 2, 'harga_jual' => 12000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Leci Squash', 'id_kategori' => 2, 'harga_jual' => 12000, 'satuan' => 'Cup'],
            ['nama_barang' => 'Orange Squash', 'id_kategori' => 2, 'harga_jual' => 12000, 'satuan' => 'Cup'],

            // Cemilan (Kategori 3)
            ['nama_barang' => 'Sosis', 'id_kategori' => 3, 'harga_jual' => 10000, 'satuan' => 'Porsi'],
            ['nama_barang' => 'Nugget', 'id_kategori' => 3, 'harga_jual' => 10000, 'satuan' => 'Porsi'],
            ['nama_barang' => 'Kentang', 'id_kategori' => 3, 'harga_jual' => 10000, 'satuan' => 'Porsi'],
            ['nama_barang' => 'Bakso Goreng', 'id_kategori' => 3, 'harga_jual' => 10000, 'satuan' => 'Porsi'],
        ];

        foreach ($items as $item) {
            $existing = Barang::where('nama_barang', $item['nama_barang'])->first();
            if ($existing) {
                $existing->update([
                    'harga_jual' => $item['harga_jual'],
                    'id_kategori' => $item['id_kategori'],
                ]);
            } else {
                Barang::create([
                    'nama_barang' => $item['nama_barang'],
                    'id_kategori' => $item['id_kategori'],
                    'harga_jual' => $item['harga_jual'],
                    'satuan' => $item['satuan'],
                    'stok' => rand(30, 100),
                    'status' => 'aktif'
                ]);
            }
        }
    }
}
