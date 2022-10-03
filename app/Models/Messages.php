<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class,'id','sender');
    }

    public function user11()
    {
        return $this->hasOne(User::class,'id','sender');
    }
}
