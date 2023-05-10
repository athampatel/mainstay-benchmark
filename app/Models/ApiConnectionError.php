<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConnectionError extends Model
{
    use HasFactory;

    protected $fillable = [
        'message'
    ];
}
