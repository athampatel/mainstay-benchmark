<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'itemcode',
        'itemcodedesc',
        'aliasitemno',
        'aliasitemdesc',
        'quantityonhand',
        'vmiprice',
        'unitprice',
        'productlinedesc',
        'product_line_id',
        'vendor_id',
        'status',
    ];
}

   
