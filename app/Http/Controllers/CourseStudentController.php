<?php

namespace App\Http\Controllers;

use App\Models\CourseStudent;
use App\Repositories\CourseStudent\CourseStudentInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseStudentController extends Controller
{
    public $courseStudentRepository;
    public function __construct(CourseStudentInterface $courseStudentRepository)
    {
        $this->courseStudentRepository = $courseStudentRepository;
    }

    public function addStudent_to_Course($course_id) {
        try {
            $this->courseStudentRepository->addNew([
                "course_id" => $course_id,
                "user_id" => Auth::id(),
            ]);
            return BaseResponse::ResWithStatus("Tham gia khóa học thành công!");
        } catch (Exception $err ) {
            return BaseResponse::ResWithStatus("Bạn đã tham gia khóa học này rồi hoặc có lỗi đã xảy ra!",404);
        }
        
       
    }

    public function getCourseByUserId(Request $request)
    {
        $data = CourseStudent::with('user')->where('id_user',$request->id_user)->get();
        // $_ = $data;
        return response()->json([
            $data
        ]);
    }
}
