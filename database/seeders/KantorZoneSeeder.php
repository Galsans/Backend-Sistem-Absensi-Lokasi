<?php

namespace Database\Seeders;

use App\Models\KantorZone;
use Illuminate\Database\Seeder;

class KantorZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KantorZone::create([
            "nama" => 'Kantor Rumah Galih',
            "latitude" => -6.258989506795552,
            "longitude" => 106.84967718057236,
            "radius_meter" => 1000,
        ]);
    }
}
