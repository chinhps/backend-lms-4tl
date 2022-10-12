<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Courses extends Model
{
    use HasFactory;
    protected $table = 'courses';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function tree_id()
    {
        return $this->hasMany(FolderTree::class, 'folder_tree_id', 'tree_id');
    }
    public function class_id()
    {
        return $this->hasOne(Classes::class, 'id', 'teacher_id');
    }
    public function subject_id()
    {
        return $this->hasOne(Subjects::class, 'id', 'subject_id');
    }
    public function teacher_id()
    {
        return $this->hasOne(User::class, 'id', 'teacher_id');
    }
    public function quiz_id()
    {
        return $this->hasMany(Quiz::class,'subject_id','subject_id');
    }
}
