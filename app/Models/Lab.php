<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends BaseModel
{
    use HasFactory;
    protected $table = "labs";
    protected $guarded = ['id'];

    public function labable()
    {
        return $this->morphTo();
    }
    
    public function deadlines()
    {
        return $this->morphOne(Deadline::class, 'deadlineable');
    }

    public function point_submit()
    {
        return $this->morphMany(PointSubmit::class, 'pointsubmitable');
    }
}
