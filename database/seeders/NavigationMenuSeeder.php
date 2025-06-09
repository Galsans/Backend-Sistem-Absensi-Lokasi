<?php

namespace Database\Seeders;

use App\Models\NavigationMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavigationMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            'User',
            'NavigationGroup',
            'NavigationMenu',
            'Category',
            'Role',
            'Kupon',
            'DashboardAdmin',
            'Absensi',
            'AbsensiPersonal',
            'KantorZone',
            'ZonaForUser',
        ];
        foreach ($menus as $name) {
            $menu = NavigationMenu::create([
                'name' => $name,
                'sort_order' => 0,
            ]);
            $menu->sort_order = $menu->id;
            $menu->save();
        }
    }
}
