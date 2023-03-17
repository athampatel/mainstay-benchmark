<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmiInventoryRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_code',
        'item_code',
        'user_detail_id',
        'old_qty_hand',
        'new_qty_hand',
        'change_user',
    ];
}
