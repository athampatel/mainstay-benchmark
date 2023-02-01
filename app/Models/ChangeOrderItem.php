<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_table_id',
        'item_code',
        'existing_quantity',
        'modified_quantity',
        'order_item_price',
    ];
}
