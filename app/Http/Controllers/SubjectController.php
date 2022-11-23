<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function list()
    {
        $data = DB::table('subjects')->join('majors', 'subjects.major_id', '=', 'majors.id')
            ->selectRaw('subjects.id, subjects.code, subjects.name, subjects.status, subjects.major_id, majors.name as major_name')->orderBy('id', 'desc')->paginate(10);
        return response()->json($data);
    }
    public function new(Request $request)
    {
        try {
            $data = DB::table('subjects')->insert([
                'major_id' => $request->major_id,
                'code' => $request->code,
                'name' => $request->name,
                'slug' => Str::slug($request->name . '-' . $request->code),
                'status' => $request->status
            ]);
            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function getOne(Request $request)
    {
        $data = DB::table('subjects')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function put(Request $request)
    {
        try {
            DB::table('subjects')->where('id', $request->id)->update([
                'major_id' => $request->major_id,
                'code' => $request->code,
                'name' => $request->name,
                'slug' => Str::slug($request->name . '-' . $request->code),
                'status' => $request->status
            ]);
            return response()->json(["msg" => "Sửa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::table('subjects')->where('id', $request->id)->delete();
            return response()->json(["msg" => "Xóa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}
