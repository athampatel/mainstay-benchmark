<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_detail_id',
        'name',
        'email',
        'phone_no',
        'message',
    ];
}
