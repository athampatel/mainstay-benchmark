<?php

namespace App\Http\Controllers;

use App\Models\SalesPersons;
use Illuminate\Http\Request;

class SalesPersonController extends Controller
{
    public static function createSalesPerson($data){
            
        $sales_person = SalesPersons::create([
            'person_number' => $data['salespersonno'],
            'name' => $data['salespersonname'],
            'email'=>$data['salespersonemail']
        ]);
        
        if(!$sales_person) return false;

        return $sales_person;
    }
}
