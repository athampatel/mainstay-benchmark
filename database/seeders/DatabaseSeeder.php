<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        // $this->call(CreateAdminSeeder::class);
        // $this->call(RolePermissionSeeder::class);
        // $this->call(ApiTypeSeeder::class);
        $this->call([
            CreateAdminSeeder::class,
            RolePermissionSeeder::class,
            ApiTypeSeeder::class,
            CustomerMenuSeeder::class,
        ]);
    }
}
