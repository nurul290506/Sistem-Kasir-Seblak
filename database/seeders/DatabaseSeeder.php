<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KategoriBarang;
use App\Models\Barang;
use App\Models\Supplier;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        User::create([
            'nama_user' => 'Muazzinah Tarmizi',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        User::create([
            'nama_user' => 'Adinda Yasmin',
            'username' => 'kasir',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
            'status' => 'aktif',
        ]);

        // 2. Seed Kategori Barang
        $toppingCat = KategoriBarang::create(['nama_kategori' => 'Topping']);
        $minumanCat = KategoriBarang::create(['nama_kategori' => 'Minuman']);

        // 3. Seed Supplier
        $supplier1 = Supplier::create([
            'nama_supplier' => 'CV. Frozen Food Takengon',
            'alamat' => 'Takengon, Aceh Tengah',
            'no_hp' => '081234567890',
        ]);

        $supplier2 = Supplier::create([
            'nama_supplier' => 'Toko Sembako Lhokseumawe',
            'alamat' => 'Batuphat, Lhokseumawe',
            'no_hp' => '082345678901',
        ]);

        // 4. Seed Barang (Toppings & Drinks)
        $toppings = [
            ['nama_barang' => 'Bakso Sapi', 'harga_jual' => 2000, 'stok' => 150, 'satuan' => 'pcs'],
            ['nama_barang' => 'Sosis Ayam', 'harga_jual' => 1500, 'stok' => 120, 'satuan' => 'pcs'],
            ['nama_barang' => 'Ceker Ayam', 'harga_jual' => 1000, 'stok' => 80, 'satuan' => 'pcs'],
            ['nama_barang' => 'Dumpling Keju', 'harga_jual' => 2500, 'stok' => 90, 'satuan' => 'pcs'],
            ['nama_barang' => 'Chikuwa', 'harga_jual' => 1500, 'stok' => 110, 'satuan' => 'pcs'],
            ['nama_barang' => 'Crab Stick', 'harga_jual' => 2000, 'stok' => 100, 'satuan' => 'pcs'],
            ['nama_barang' => 'Mie Kuning', 'harga_jual' => 2000, 'stok' => 200, 'satuan' => 'porsi'],
            ['nama_barang' => 'Kerupuk Orange', 'harga_jual' => 1000, 'stok' => 300, 'satuan' => 'porsi'],
            ['nama_barang' => 'Siomay Kering', 'harga_jual' => 1500, 'stok' => 250, 'satuan' => 'pcs'],
        ];

        foreach ($toppings as $t) {
            Barang::create(array_merge($t, [
                'id_kategori' => $toppingCat->id_kategori,
                'status' => 'aktif',
            ]));
        }

        $drinks = [
            ['nama_barang' => 'Es Teh Manis', 'harga_jual' => 5000, 'stok' => 100, 'satuan' => 'gelas'],
            ['nama_barang' => 'Es Jeruk Peras', 'harga_jual' => 7000, 'stok' => 80, 'satuan' => 'gelas'],
            ['nama_barang' => 'Air Mineral', 'harga_jual' => 4000, 'stok' => 120, 'satuan' => 'botol'],
            ['nama_barang' => 'Nutrisari Es', 'harga_jual' => 5000, 'stok' => 100, 'satuan' => 'gelas'],
        ];

        foreach ($drinks as $d) {
            Barang::create(array_merge($d, [
                'id_kategori' => $minumanCat->id_kategori,
                'status' => 'aktif',
            ]));
        }
    }
}
