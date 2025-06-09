<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('absens')->insert([
        //     [
        //         'user_id'       => 1,
        //         'category_id'   => 1,
        //         'tanggal_awal'  => Carbon::now()->subDays(5),
        //         'tanggal_akhir' => Carbon::now(),
        //         'keterangan'    => 'Permintaan penggunaan fasilitas ruang meeting.',
        //         'status'        => 'pending',
        //         'bukti'         => null,
        //     ],
        //     [
        //         'user_id'       => 2,
        //         'category_id'   => 2,
        //         'tanggal_awal'  => Carbon::now()->subDays(3),
        //         'tanggal_akhir' => Carbon::now()->addDays(2),
        //         'keterangan'    => 'Pengajuan peminjaman alat presentasi.',
        //         'status'        => 'terkonfirmasi',
        //         'bukti'         => null,
        //     ],
        // ]);

        $statuses = ['masuk', 'izin']; // <- perbaiki juga nilai status
        for ($i = 1; $i <= 10; $i++) {
            $status = $statuses[array_rand($statuses)];

            DB::table('absens')->insert([
                'user_id'       => rand(1, 2), // pastikan user_id ini ada di tabel users
                'category_id'   => $status === 'izin' ? rand(1, 3) : null,
                'tanggal_awal'  => Carbon::now()->subDays(rand(1, 30)),
                'tanggal_akhir' => Carbon::now()->addDays(rand(1, 5)),
                'keterangan'    => 'Keterangan data ke-' . $i,
                'status'        => $status,
                'latitude'      => -6.2 + (rand(0, 100) / 1000), // sekitar Jakarta
                'longitude'     => 106.8 + (rand(0, 100) / 1000), // sekitar Jakarta
                'hr_status'     => 'pending', // default HR status
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
