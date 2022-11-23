<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table = "subjects";

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

}