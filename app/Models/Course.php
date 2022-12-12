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

    public function subject()
    {
        return $this->hasOne(Subject::class, 'id', 'subject_id');
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

    public function point_submits()
    {
        return $this->hasMany(PointSubmit::class, 'course_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class,'course_id','id');
    }
}
