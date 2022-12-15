<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointSubmit extends Model
{
    use HasFactory;
    protected $table = "point_submit";
    protected $guarded = ['id'];

    public function pointsubmitable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

}
