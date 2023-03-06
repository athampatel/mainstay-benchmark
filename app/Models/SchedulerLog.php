<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulerLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_details_id',
        'resource',
        'index_type',
        'filter',
        'total',
        'current_page',
        'completed'
    ];

}
