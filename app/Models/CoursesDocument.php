<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesDocument extends Model
{
    use HasFactory;
    protected $table = "courses_document";

    public function course_id()
    {
        $this->hasOne(Courses::class,'course_id','id');
    }
}
