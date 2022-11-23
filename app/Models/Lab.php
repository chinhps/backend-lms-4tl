<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;
    protected $table = "labs";

    public function labable()
    {
        return $this->morphTo();
    }
    public function deadlines()
    {
        return $this->morphOne(Deadline::class, 'deadlineable');
    }
}
