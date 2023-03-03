<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleByProductLine extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_details_id',
        'ProductLine',
        'year',
        'month',
        'value'
    ];
}
