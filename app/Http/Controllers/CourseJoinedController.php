<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseJoinedController extends Controller
{
    public function getMyCourse(Request $request)
    {
        $limit = $request->limit ?? 3;
        $data = DB::table('course_joined')
            ->selectRaw('*')
            ->join('courses', 'course_joined.course_id', '=', 'courses.id')
            ->where('user_id', Auth::id())
            // ->orderBy('id','asc')
            ->take($limit)->get();
        return response()->json($data);
    }

    public function joinCourse(Request $request)
    {

        try {
            DB::table('course_joined')->insert([
                'course_id' => $request->idCourse,
                'user_id' => Auth::id(),
            ]);
            return response()->json(['status' => 200, 'msg' => 'Tham gia thành công!']);
        } catch (Exception $e) {
            return response()->json(['status' => 201, 'msg' => 'Đã tham gia khóa học!']);
        }
    }
}
