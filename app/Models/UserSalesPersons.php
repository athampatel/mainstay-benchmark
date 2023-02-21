<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSalesPersons extends Model
{
    use HasFactory;

    protected $fillable = ['user_details_id','sales_person_id'];
}
