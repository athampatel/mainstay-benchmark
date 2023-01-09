<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesPersons extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['person_number','name','email'];
}
