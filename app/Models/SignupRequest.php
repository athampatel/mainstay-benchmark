<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignupRequest extends Model
{
    use HasFactory;
    protected $fillable = [        
        'email',
        'full_name',
        'company_name',
        'phone_no'
    ];
}
