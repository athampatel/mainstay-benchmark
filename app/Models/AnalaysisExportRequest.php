<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalaysisExportRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_no',
        'user_detail_id',
        'ardivisiono',
        'start_date',
        'end_date',
        'unique_id', 
        'type', 
        'status',
        'year',
        'request_body',
        'resource',
        'is_analysis'
    ];
}
