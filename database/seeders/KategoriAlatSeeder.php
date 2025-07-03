<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriAlat;

class KategoriAlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Elektronika',
            ],
            [
                'nama_kategori' => 'Pengukuran',
            ],
            [
                'nama_kategori' => 'Generator',
            ],
            [
                'nama_kategori' => 'Power',
            ],
            [
                'nama_kategori' => 'Analisis',
            ],
            [
                'nama_kategori' => 'Optik',
            ],
            [
                'nama_kategori' => 'Mekanik',
            ],
            [
                'nama_kategori' => 'Thermal',
            ]
        ];

        foreach ($kategoris as $kategori) {
            KategoriAlat::firstOrCreate($kategori);
        }
    }
}
