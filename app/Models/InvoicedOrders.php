<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicedOrders extends Model
{
    use HasFactory;
    protected $fillable = [        
        'invoiceno', 
        'sale_orders_id', 
        'invoicedate', 
        'customerpono', 
        'termscode', 
        'headerseqno',
        'ardivisionno'
     ];
}
