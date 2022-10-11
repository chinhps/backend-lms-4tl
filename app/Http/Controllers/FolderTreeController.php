<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\FolderTree;
use Illuminate\Http\Request;

class FolderTreeController extends Controller
{
    public function list_parent(Request $request)
    {
        $data = FolderTree::where('parent_tree_id', 0)->get();
        return response()->json($data);
    }
    public function list_child($params)
    {
        //dd($data);
        $lastChild = explode('/', $params);
        $lastChild = $lastChild[count($lastChild) - 1];
        $data = FolderTree::where('parent_tree_id', $lastChild)->get();
        $check_type = FolderTree::find($lastChild);
        if ($check_type->type == 1) // class
        {
            $data = Courses::with(['teacher_id' => function ($query) {
                $query->select('id', 'name')->where('status', 1);
            }, 'subject_id'])->where('tree_id', $lastChild)->get();
            return response()->json($data);
        }
        return response()->json($data);
    }
}
