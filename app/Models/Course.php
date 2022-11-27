<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = "courses";

    public function quizs()
    {
        return $this->morphMany(Quiz::class, 'quizable');
    }

    public function labs()
    {
        return $this->morphMany(Lab::class, 'labable');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function course_joined()
    {
        return $this->hasMany(CourseJoined::class);
    }

}