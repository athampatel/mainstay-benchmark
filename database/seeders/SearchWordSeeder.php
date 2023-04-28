<?php

namespace Database\Seeders;

use App\Models\SearchWord;
use Illuminate\Database\Seeder;

class SearchWordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $is_search_word = SearchWord::find(1);
        if(!$is_search_word){
            SearchWord::create([
                'name' => 'Roles',
                'link' => '/admin/roles',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Customers',
                'link' => '/admin/customers',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Admins',
                'link' => '/admin/admins',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Signups',
                'link' => '/admin/signups',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Dashboard',
                'link' => '/admin',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Create Role',
                'link' => '/admin/roles/create',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Create Admin',
                'link' => '/admin/admins/create',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Create Staff',
                'link' => '/admin/admins/create',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Regional Managers',
                'link' => '/admin/admins/manager',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Create Customer',
                'link' => '/admin/customers/create',
                'type' => 1
            ]);
            SearchWord::create([
                'name' => 'Profile',
                'link' => '/admin/profile',
                'type' => 1
            ]);
            // customers
            SearchWord::create([
                'name' => 'Dashboard',
                'link' => '/dashboard',
                'type' => 2
            ]);
            SearchWord::create([
                'name' => 'Invoice',
                'link' => '/invoice',
                'type' => 2
            ]);
            SearchWord::create([
                'name' => 'Open Orders',
                'link' => '/open-orders',
                'type' => 2
            ]);
            SearchWord::create([
                'name' => 'VMI',
                'link' => '/vmi-user',
                'type' => 2
            ]);
            SearchWord::create([
                'name' => 'Analysis',
                'link' => '/analysis',
                'type' => 2
            ]);
            SearchWord::create([
                'name' => 'Account Settings',
                'link' => '/account-settings',
                'type' => 2
            ]);
            SearchWord::create([
                'name' => 'Help',
                'link' => '/help',
                'type' => 2
            ]);
            SearchWord::create([
                'name' => 'Profile',
                'link' => '/account-settings',
                'type' => 2
            ]);
        }
    }
}