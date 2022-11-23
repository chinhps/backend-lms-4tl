<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MajorController extends Controller
{
    public function list()
    {
        $data = DB::table('majors')->orderBy('id', 'desc')->paginate(10);
        return response()->json($data);
    }
    public function getById(Request $request)
    {
        $data = DB::table('majors')->where('id', $request->id)->first();
        return response()->json($data);
    }
    public function delete(Request $request)
    {
        try {
            DB::table('majors')->where('id', $request->id)->delete();
            return response()->json(["msg" => "Xóa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function new(Request $request)
    {
        try {
            $data = DB::table('majors')->insert([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'branchable_type' => $request->branchable_type,
                'status' => $request->status,
            ]);
            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function getOne(Request $request)
    {
        $data = DB::table('majors')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function put(Request $request)
    {
        try {
            DB::table('majors')->where('id', $request->id)->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name . '-' . $request->code),
                'branchable_type' => $request->branchable_type,
                'status' => $request->status,
            ]);
            return response()->json(["msg" => "Sửa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}
