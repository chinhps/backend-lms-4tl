<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\CoursesDocument;
use App\Models\Labs;
use App\Models\Quiz;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function getCourseById($id)
    {
        $id_class = 3;//3;
        $data = Courses::with('class_id','subject_id','teacher_id')->find($id);
        $getListQuiz = Quiz::where('subject_id',$data['subject_id'])->get();
        $getDocument = CoursesDocument::where('subject_id',$data['subject_id'])
        ->whereIn('audience',[0,$id_class])
        ->get();
        $getLabs = Labs::where('subject_id',$data['subject_id'])
        ->whereIn('audience',[0,$id_class])
        ->whereIn('type',['lab'])
        ->get();
        $getAsms = Labs::where('subject_id',$data['subject_id'])
        ->whereIn('audience',[0,$id_class])
        ->whereIn('type',['asm'])
        ->get();
        return response()->json([
            "course_info" => $data,
            "listQuiz" => $getListQuiz,
            "listDocument" => $getDocument,
            "listLabs" => $getLabs,
            "listAsms" => $getAsms
        ]);
    }
}
