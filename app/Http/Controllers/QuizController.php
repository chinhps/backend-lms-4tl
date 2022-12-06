<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuizWorkingResource;
use App\Models\Course;
use App\Models\PointSubmit;
use App\Models\QuestionBank;
use App\Models\Quiz;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{

    public function joinQuiz(Request $request)
    {
        $password = $request->input('password') ?? null;
        $slug_quiz = $request->input('slug_quiz');
        $slug_course = $request->input('slug_course');

        $quiz = Quiz::with('deadlines', 'quizable', 'point_submit')->where('slug', $slug_quiz)->first();

        if (!isset($quiz->deadlines)) {
            return BaseResponse::ResWithStatus("Bài tập này chưa được Giảng viên cấu hình!", 403);
        }

        if ($quiz->deadlines->password != null && $password != $quiz->deadlines->password) {
            return BaseResponse::ResWithStatus("Mật khẩu sai không thể làm bài!", 403);
        }

        # lấy bài làm của sinh viên đang đăng nhập
        $point_submit = $quiz->point_submit()->where([
            'user_id' => Auth::id(),
            'status' => 1
        ])->count();

        if ($point_submit >= $quiz->deadlines->max_working) {
            return BaseResponse::ResWithStatus("Số lần làm bài quá giới hạn quy định!", 403);
        }

        # lấy bài chưa làm
        $point_submit = $quiz->point_submit()->where([
            'user_id' => Auth::id(),
            'status' => 0
        ])->get();

        # không có bài đang làm và chưa đạt giới hạn thì tạo mới
        if (count($point_submit) == 0) {

            # lục ngân hàng câu hỏi để lấy
            $list_question = QuestionBank::where('subject_id', $quiz->quizable->subject_id ?? $quiz->quizable->id)
                ->where('level', $quiz->level)
                ->inRandomOrder()
                ->take($quiz->deadlines->questions)
                ->get();

            if (count($list_question) < $quiz->deadlines->questions) {
                return BaseResponse::ResWithStatus("Không đủ số lượng câu hỏi vui lòng báo cáo Giảng viên!", 403);
            }

            $course = Course::where('slug', $slug_course)->first();
            # lưu kết quả làm bài của sinh viên
            $quiz->point_submit()->create([
                'user_id' => Auth::id(),
                'course_id' => $course->id ?? 0,
                'content' => json_encode($list_question->map(function ($item) {
                    return [
                        'question_bank_id' => $item->id,
                        'chooses' => []
                    ];
                })),
                'point' => 0,
                'status' => 0, # đang làm
            ]);
        } else {
            $now = Carbon::now();
            $point_submit_working = $point_submit[0];
            $re_list_questions = Arr::pluck(json_decode($point_submit_working->content, true), 'question_bank_id');
            # hiển thị lại câu hỏi cũ
            $list_question = QuestionBank::whereIn('id',$re_list_questions)->get();

            $check_time = $quiz->deadlines->max_time_working - (strtotime($now) - strtotime($point_submit_working->created_at));
            if($check_time <= 1) {
                return BaseResponse::ResWithStatus("Hết thời gian làm bài!", 403);
            }
            $quiz->deadlines->max_time_working = $check_time;
        }

        return new QuizWorkingResource([
            "data" => [
                'info_quiz' => $quiz,
                'list_questions' => $list_question
            ]
        ]);
    }
}
