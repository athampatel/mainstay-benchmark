<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'ardivisionno',
        'customerno',
        'customername',
        'addressline1',
        'addressline2',
        'addressline3',
        'city',
        'state',
        'zipcode',
        'email',
        'user_id',
        'vmi_companycode'
    ];

    public function User(){
        return $this->hasOne(User::class,'id','user_id');
    }

    // public function salesPerson(){
    //     return $this->hasOne(SalesPersons::class, 'user_details_id', 'id')
    //         ->join('user_sales_persons', 'sales_persons.id', '=', 'user_sales_persons.sales_person_id');
    // }
    // User
    // user_sales_person
    public function userSalesPerson(){
        return $this->hasOne(UserSalesPersons::class,'user_details_id','id');
    }
    public function salesPerson(){
        return $this->hasOne(UserSalesPersons::class, 'user_details_id', 'id');
            // ->join('sales_persons', 'sales_persons.id', '=', 'user_sales_persons.sales_person_id');
    }
}
