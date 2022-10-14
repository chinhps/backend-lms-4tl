<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;
    protected $table = "question_bank";
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
