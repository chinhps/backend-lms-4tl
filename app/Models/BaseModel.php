<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Auth::id() ?? 0;
        });
        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? 0;
        });
        static::deleting(function ($model) {
            $model->deleted_by = Auth::id() ?? 0;
            $model->save();
        });
    }
}
