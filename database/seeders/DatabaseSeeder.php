<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(NavigationMenuSeeder::class);
        $this->call(NavigationGroupSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(KuponSeeder::class);
        // $this->call(AbsensiSeeder::class);
        $this->call(KantorZoneSeeder::class);
    }
}
