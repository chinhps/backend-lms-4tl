<?php

namespace App\Http\Controllers;

use App\Models\AllBranch;
use App\Models\Courses;
use App\Models\DocumentGroup;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class BranchController extends Controller
{

    protected $full_data;

    public function getAllBranchWithChild($branches, $parent = 0, $level = 0)
    {
        foreach ($branches as $key => $branch) {
            if ($branch['parent'] == $parent) {
                $this->full_data[] = [
                    'key' => $key,
                    'level' => $level,
                    ...$branch
                ];
                unset($branches[$key]);
                $this->getAllBranchWithChild($branches, $branch['id'], $levels = $level + 1);
            }
        }
    }

    public function list_parent(Request $request)
    {
        $slugOridParent = $request->page ?? null;
        $table = $request->table ?? null;

        $primayKey = [
            'subjects' => 'major_id',
            'courses' => 'subject_id'
        ];

        if ($table) {
            $data_child = DB::table($table)->where('slug', $slugOridParent)->first();

            if ($data_child) {
                $data_branch = DB::table($data_child->branchable_type)
                    ->where($primayKey[$data_child->branchable_type], $data_child->id)
                    ->get();

                return response()->json($data_branch);
            }
        }

        $data_branch = AllBranch::get()->toArray();

        foreach ($data_branch as $branch) {
            if ($branch['branchable_type'] != null) {
                $data_child = DB::table($branch['branchable_type']);
                if ($branch['branchable_id']) {
                    $data_child = $data_child->where('group_id', $branch['branchable_id']);
                }
                $data_child = $data_child->get();

                foreach ($data_child as $child) {
                    $data_branch[] = [
                        "id" => $branch['branchable_type'] . $branch['id'],
                        "name" => $child->name,
                        "parent" => $branch['id'],
                        "branchable_id" => $child->id,
                        "thumb" => "https://i.imgur.com/v4YUHjU.png",
                        "branchable_type" => $branch['branchable_type'],
                        "slug" => $child->slug ?? Str::slug($child->name),
                    ];
                }
            }
        }
        $this->getAllBranchWithChild($data_branch);

        if ($slugOridParent) {
            $slugId = AllBranch::where('slug', $slugOridParent)->first();
            foreach ($this->full_data as $key => $slugBranch) {
                if ($slugBranch['parent'] != ($slugId->id ?? null)) {
                    unset($this->full_data[$key]);
                }
            }
            $this->full_data = array_values($this->full_data);
        }

        return response()->json($this->full_data);
        // return view('test',['data' => $this->full_data]);
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
    public function put(Request $request)
    {
        try {
            DB::table('all_branch')->where('id', $request->id)->update([
                'name' => $request->name,
                'thumb' => $request->thumb,
                'parent' => $request->parent,
                'slug' => Str::slug($request->name . '-' . Str::random(8)),
            ]);
            return response()->json(["msg" => "Sửa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function new(Request $request)
    {
        try {
            $data = DB::table('all_branch')->insert([
                'name' => $request->name,
                'thumb' => $request->thumb,
                'parent' => $request->parent,
                'slug' => Str::slug($request->name . '-' . Str::random(8)),
            ]);
            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function getOneBySlug(Request $request)
    {
        $data = DB::table('all_branch')->where('slug', $request->slug)->first();
        return response()->json($data);
    }
    public function upsert(Request $request)
    {
        $id = $request->input('id') ?? null;
        $name = $request->input('name');
        $parent = $request->input('parent');
        $thumb = $request->input('thumb');

        try {
            $dataUpsert = [
                'name' => $name,
                'parent' => $parent,
                'thumb' => $thumb,
                'slug' => Str::slug($name . Str::random(8)),
            ];

            AllBranch::updateOrCreate(['id' => $id], $dataUpsert);
            return BaseResponse::ResWithStatus($id ? "Sửa thành công!" : 'Tạo mới thành công!', 200);
        } catch (\Exception $err) {
            return $err;
            // return BaseResponse::ResWithStatus($id ? "Có lỗi khi sửa!" : 'Có lỗi xảy ra khi tạo mới!', 500);
        }
    }
}
