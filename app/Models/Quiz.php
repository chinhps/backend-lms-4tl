<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $table = "quizs";

    public function deadlines()
    {
        return $this->morphOne(Deadline::class, 'deadlineable');
    }

    public function point_submit()
    {
        return $this->morphMany(PointSubmit::class, 'pointSubmitable');
    }

}