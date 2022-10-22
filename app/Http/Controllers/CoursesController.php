<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\CoursesDocument;
use App\Models\Labs;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoursesController extends Controller
{
    public function getCourseById($id)
    {

        # kiểm tra người dùng đã tham gia khóa học này chưa

        $id_class = 3;//3;
        $data = Courses::with('class_id','subject_id','teacher_id')->find($id);
        $getListQuiz = Quiz::where('subject_id',$data['subject_id'])->get();

        $getDocument = CoursesDocument::where('subject_id',$data['subject_id'])
            ->whereIn('audience',[0,$id_class])
            ->get();

        $getLabs = Labs::where('subject_id',$data['subject_id'])
            ->whereIn('audience',[0,$id_class])
            ->get();
        
        // $a = DB::table('labs')->leftJoin('deadline',function ($join) {
        //     $join->where('deadline.audience', '=','labs.id')
        //     ->where('deadline.type', 'labs');
        // })->select('*')->get()->toArray();
        

        return response()->json([
            "course_info" => $data,
            "listQuiz" => $getListQuiz,
            "listDocument" => $getDocument,
            "listLabs" => $getLabs
        ]);
    }
}
