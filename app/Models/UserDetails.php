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
}
