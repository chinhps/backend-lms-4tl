<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolderTree extends Model
{
    use HasFactory;
    protected $table = "folder_tree";
    protected $primaryKey = "folder_tree_id";


}
