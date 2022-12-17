<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllBranch extends Model
{
    use HasFactory;
    protected $table = "all_branch";
    protected $guarded = ['id'];

    public function branchable()
    {
        return $this->morphTo();
    }
    
    public function majors()
    {
        return $this->morphToModel(Major::class);
    }

}