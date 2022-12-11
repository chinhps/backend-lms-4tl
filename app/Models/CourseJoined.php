<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseJoined extends Model
{
    use HasFactory;
    protected $table = "course_joined";

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

}