<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\FolderTree;
use App\Repositories\Branch\BranchInterface;
use Illuminate\Http\Request;
use App\AllBranch;

class BranchController extends Controller
{
 
    public function list_parent()
    {
        $data = AllBranch::where('parent',0)->get();
        return response()->json($data);
    }

    public function list_child($params)
    {
        # lấy phần tử cuối cùng
        $lastChild = explode('/', $params);
        $lastChild = $lastChild[count(explode('/', $params)) - 1];

        # lấy dữ liệu từ repo
        $data = $this->branchRepository->childs($lastChild);

        # không có data thì kiểm tra trạng thái
        if($data->count() == 0) {
            $check_type = $this->branchRepository->findOne($lastChild);
            if ($check_type->type == 1) // class
            {
                $data = Courses::with(['teacher_id' => function ($query) {
                    $query->select('id', 'name')->where('status', 1);
                }, 'subject_id','class_id'])->where('tree_id', $lastChild)->get();
            }
        }
       
        return response()->json($data);
    }
}
