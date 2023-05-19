<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['customerno'];

    public function UserDetails(){
        return $this->hasMany(UserDetails::class,'customerno','customerno');
    }
}
