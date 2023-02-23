<?php

namespace Database\Seeders;

use App\Models\ApiType;
use Illuminate\Database\Seeder;

class ApiTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $count = ApiType::count();
        if($count < 1){
            ApiType::create([
                'name' => 'salesorders'
            ]);
            ApiType::create([
                'name' => 'customers'
            ]);
        }
    }
}
