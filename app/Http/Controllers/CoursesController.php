<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function getCourseById(Request $request)
    {
        $data = Courses::with('subject_id', "teacher")->where('id', $request->id)->get();
        // $_ = $data;
        return response()->json([
            $data
        ]);
    }
}
