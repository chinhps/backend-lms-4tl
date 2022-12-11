<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends BaseModel
{
    use HasFactory;
    protected $table = "documents";
    protected $guarded = ['id'];

    public function documentable()
    {
        return $this->morphTo();
    }

}