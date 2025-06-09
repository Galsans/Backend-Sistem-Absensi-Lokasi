<?php

namespace Database\Seeders;

use App\Models\NavigationGroup;
use Illuminate\Database\Seeder;

class NavigationGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'role_id' => 1,
                'navigation_menu_id' => 1,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
            [
                'role_id' => 1,
                'navigation_menu_id' => 2,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
            [
                'role_id' => 1,
                'navigation_menu_id' => 3,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
            [
                'role_id' => 1,
                'navigation_menu_id' => 4,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
            [
                'role_id' => 1,
                'navigation_menu_id' => 5,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
            [
                'role_id' => 1,
                'navigation_menu_id' => 6,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
            [
                'role_id' => 1,
                'navigation_menu_id' => 8,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
            [
                'role_id' => 1,
                'navigation_menu_id' => 10,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
            [
                'role_id' => 2,
                'navigation_menu_id' => 9,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
            [
                'role_id' => 2,
                'navigation_menu_id' => 11,
                'create_access' => 1,
                'read_access' => 1,
                'update_access' => 1,
                'delete_access' => 1,
            ],
        ];
        foreach ($data as $item) {
            NavigationGroup::updateOrCreate(
                $item
            );
        }
    }
}
