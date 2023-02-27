<?php

namespace Database\Seeders;

use App\Models\CustomerMenu;
use Illuminate\Database\Seeder;

class CustomerMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customerMenu = CustomerMenu::find(1);
        if(!$customerMenu){
            CustomerMenu::create([
                'code' => 'auth.customer.dashboard',
                'name' => 'Customer Dashboard'
            ]);
            CustomerMenu::create([
                'code' => 'auth.customer.invoice',
                'name' => 'Customer Invoice'
            ]);
            CustomerMenu::create([
                'code' => 'auth.customer.open-orders',
                'name' => 'Customer Open-orders'
            ]);
            CustomerMenu::create([
                'code' => 'auth.customer.vmi',
                'name' => 'Customer VMI'
            ]);
            CustomerMenu::create([
                'code' => 'auth.customer.analysis',
                'name' => 'Customer Analysis'
            ]);
            CustomerMenu::create([
                'code' => 'auth.customer.account-settings',
                'name' => 'Customer Account Settings'
            ]);
            CustomerMenu::create([
                'code' => 'auth.customer.help',
                'name' => 'Customer Help'
            ]);
            CustomerMenu::create([
                'code' => 'auth.customer.logout',
                'name' => 'Customer Logout'
            ]);
            CustomerMenu::create([
                'code' => 'auth.customer.change-order',
                'name' => 'Customer Change Order Request'
            ]);
        }
    }
}
