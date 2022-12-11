<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function getAll(Request $request)
    {
        $limit = $request->limit ?? 3;
        $data = DB::table('news')->take($limit)->get();
        return response()->json($data);
    }

    public function delete(Request $request)
    {
        try {
            DB::table('news')->where('id', $request->id)->delete();
            return response()->json(["msg" => "Xóa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
    public function new(Request $request)
    {
        try {
            $file = $request->file('thumb');
            $name = time() . rand(1, 100) . '.' . $file->extension();
            $file->move(public_path('files'), $name);

            $data = DB::table('news')->insert([
                'thumb' => env('APP_URL') . '/files/' . $name,
                'user_id' => $request->user_id,
                'title' => $request->title,
                'content' => $request->content,
            ]);
            return response()->json(["msg" => "Thêm thành công!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function getOne(Request $request)
    {
        $data = DB::table('news')->where('id', $request->id)->first();
        return response()->json($data);
    }

    public function put(Request $request)
    {
        try {
            DB::table('news')->where('id', $request->id)->update([
                'thumb' => $request->thumb,
                'user_id' => $request->user_id,
                'title' => $request->title,
                'content' => $request->content,
            ]);
            return response()->json(["msg" => "Sửa thành công id $request->id!"]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }
}
