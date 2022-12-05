<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;
    protected $table = "question_bank";

    public function course()
    {
        return $this->hasOne(Course::class,'id','subject_id');
    }

}
