<?php

namespace Database\Seeders;

use App\Models\Kupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KuponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kupon::create([
            "user_id" => 1,
            "jumlah" => 10,
        ]);
        Kupon::create([
            "user_id" => 2,
            "jumlah" => 10,
        ]);
    }
}
