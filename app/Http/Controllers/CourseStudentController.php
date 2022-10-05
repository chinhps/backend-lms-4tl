<?php

namespace App\Http\Controllers;

use App\Models\CourseStudent;
use Illuminate\Http\Request;

class CourseStudentController extends Controller
{
    public function getCourseByUserId(Request $request)
    {
        $data = CourseStudent::with('user')->where('id_user',$request->id_user)->get();
        // $_ = $data;
        return response()->json([
            $data
        ]);
    }
}
