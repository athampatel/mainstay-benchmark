<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVendors extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendor_code','vendor_name'
    ];
}
