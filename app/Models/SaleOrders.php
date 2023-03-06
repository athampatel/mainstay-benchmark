<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrders extends Model
{
    use HasFactory;
    protected $fillable = [        
        'salesorderno', 
        'user_details_id', 
        'orderdate', 
        'shiptoname', 
        'shiptoaddress1',
        'shiptoaddress2', 
        'shiptoaddress3', 
        'shiptocity', 
        'shiptostate', 
        'shiptozipcode', 
        'shipvia', 
        'taxablesalesamt', 
        'nontaxablesalesamt', 
        'freightamt', 
        'salestaxamt',
    ];
}


