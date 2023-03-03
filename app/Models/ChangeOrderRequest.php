<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeOrderRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'order_no',
        'request_status',
        'status_detail',
        'user_details_id',
        'updated_by',
        'sync',
        'ordered_date',
    ];
}
