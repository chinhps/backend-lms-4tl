<?php

namespace App\Http\Controllers;

use App\Models\AllBranch;
use App\Models\Courses;
use App\Models\DocumentGroup;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{

    protected $full_data;

    public function getAllBranchWithChild($branches,$parent = 0,$level = 0)
    {
        foreach($branches as $key => $branch) {
            if($branch['parent'] == $parent) {
                $this->full_data[] = [
                    'key' => $key,
                    'name' => $branch['name'],
                    'slug' => $branch['slug'],
                    'level' => $level  
                ];
                unset($branches[$key]);
                $this->getAllBranchWithChild($branches,$branch['id'],$levels = $level + 1);
            }
        }
    }

    public function list_parent()
    {

        $data_branch = AllBranch::get()->toArray();
        foreach($data_branch as $branch) {

            if($branch['branchable_type'] != null) {

                $data_child = DB::table($branch['branchable_type']);
                if($branch['branchable_id']) {
                    $data_child = $data_child->where('group_id',$branch['branchable_id']);
                }
                $data_child = $data_child->get();

                foreach ($data_child as $child) {
                    $data_branch[] = [
                        "id" => $branch['branchable_type'] . $branch['id'],
                        "name" => $child->name,
                        "parent" => $branch['id'],
                        "branchable_id" => 0,
                        "branchable_type" => null,
                        "slug" => "kho-hoc-lieu"
    
                    ];
                }
            }
        }
        $this->getAllBranchWithChild($data_branch);
        // return response()->json($this->full_data);
        return view('test',['data' => $this->full_data]);
    }

    public function list_child($params)
    {
        # lấy phần tử cuối cùng
        $lastChild = explode('/', $params);
        $lastChild = $lastChild[count(explode('/', $params)) - 1];

        # lấy dữ liệu từ repo
        $data = $this->branchRepository->childs($lastChild);

        # không có data thì kiểm tra trạng thái
        // if($data->count() == 0) {
        //     $check_type = $this->branchRepository->findOne($lastChild);
        //     if ($check_type->type == 1) // class
        //     {
        //         $data = Courses::with(['teacher_id' => function ($query) {
        //             $query->select('id', 'name')->where('status', 1);
        //         }, 'subject_id','class_id'])->where('tree_id', $lastChild)->get();
        //     }
        // }
       
        return response()->json($data);
    }
}
