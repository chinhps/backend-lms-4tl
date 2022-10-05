<?php

namespace App\Http\Controllers;

use App\Models\CourseStudent;
use Illuminate\Http\Request;

class CourseStudentController extends Controller
{
    public function getCourseByUserId(Request $request)
    {
        $data = CourseStudent::query();
        $course = $data->where('user_id', $request->id)->first();
        return response()->json([
            $data, $course
        ]);
    }
}
