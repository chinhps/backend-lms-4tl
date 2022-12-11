<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends BaseModel
{
    use HasFactory;
    protected $table = "quizs";
    protected $guarded = ['id'];

    public function deadlines()
    {
        return $this->morphOne(Deadline::class, 'deadlineable');
    }

    public function quizable()
    {
        return $this->morphTo();
    }

    public function point_submit()
    {
        return $this->morphMany(PointSubmit::class, 'pointsubmitable');
    }

}