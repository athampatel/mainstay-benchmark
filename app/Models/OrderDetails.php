<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $fillable = [        
       'sale_orders_id', 
       'product_id',
       'quantitypurchased', 
       'quantityshipped', 
       'dropship', 
       'unitprice'
    ];
}
