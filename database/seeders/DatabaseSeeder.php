<?php

namespace Database\Seeders;

use App\Models\ScheduleAvailability;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            StaffSeeder::class,
            ArtikelSeeder::class,
            JenisPengujianSeeder::class,
            KategoriAlatSeeder::class,
            StaffSeeder::class,
            AlatSeeder::class,
        ]);
    }
}
