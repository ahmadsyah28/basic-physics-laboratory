<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BiodataPengurus;
use App\Models\Gambar;
use Illuminate\Support\Str;

class StaffSeeder extends Seeder
{
    public function run()
    {
        $staffData = [
            [
                'nama' => 'Dr. Nasrullah, S.Si, M.Si.,M.Sc',
                'jabatan' => 'Kepala Laboratorium',
                'foto' => 'ketua-lab.jpg'
            ],
            [
                'nama' => 'Intan Mulia Sari, S.Si., M.Si.',
                'jabatan' => 'Tenaga Pengajar',
                'foto' => 'tenaga-pengajar-1.png'
            ],
            [
                'nama' => 'Anla Fet Hardi, S.Si., M.Si.',
                'jabatan' => 'Tenaga Pengajar',
                'foto' => 'tenaga-pengajar-2.jpg'
            ],
            [
                'nama' => 'Vikah Suci Novianti, S.Si',
                'jabatan' => 'Laboran',
                'foto' => 'laboran-2.jpg'
            ],
            [
                'nama' => 'Dini Rizqi Dwi Kunti Siregar, S.Si., M.Si',
                'jabatan' => 'Laboran',
                'foto' => 'laboran-1.jpg'
            ]
        ];

        foreach ($staffData as $data) {
            // Buat pengurus
            $pengurus = BiodataPengurus::create([
                'id' => Str::uuid(),
                'nama' => $data['nama'],
                'jabatan' => $data['jabatan']
            ]);

            // Buat gambar jika ada foto
            if (isset($data['foto'])) {
                Gambar::create([
                    'id' => Str::uuid(),
                    'pengurus_id' => $pengurus->id,
                    'url' => 'staff/' . $data['foto'],
                    'kategori' => 'PENGURUS'
                ]);
            }
        }
    }
}
