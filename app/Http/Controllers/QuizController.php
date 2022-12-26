<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizWorkingResource;
use App\Models\Course;
use App\Models\PointSubmit;
use App\Models\QuestionBank;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuizController extends Controller
{

    public function delete($slug)
    {
        $data = Quiz::where('slug', $slug)->first()->delete();
        if ($data) {
            return BaseResponse::ResWithStatus('Xóa thành công!');
        }
        return BaseResponse::ResWithStatus('Không tìm thấy để xóa!', 404);
    }
    public function getOne($slug)
    {
        $data = Quiz::where('slug', $slug)->first();
        return new QuizResource($data);
    }

    public function upsert(Request $request)
    {
        $id = $request->input('id') ?? null;
        $slugCourse = $request->input('slugCourse');
        $name = $request->input('nameQuiz');
        $level = $request->input('level');
        $range = $request->input('rangeQuiz');
        $description = $request->input('description');

        try {
            $course = Course::with('quizs', 'subject.quizs')->where('slug', $slugCourse)->first();

            $dataUpsert = [
                'name' => $name,
                'level' => $level,
                'description' => ($description != 'null') ? $description : null,
                'slug' => Str::slug($name . Str::random(8)),
            ];

            if ($range == 'subjects') {
                $data_course = $course->subject->quizs()->updateOrCreate([
                    'id' => $id
                ], $dataUpsert);
            } else {
                $data_course = $course->quizs()->updateOrCreate([
                    'id' => $id
                ], $dataUpsert);
            }
            return BaseResponse::ResWithStatus(!$data_course->wasRecentlyCreated && $data_course->wasChanged() ? "Sửa thành công!" : 'Tạo mới Quiz thành công! Cần cấu hình để có thể làm bài', 200);
        } catch (\Exception $err) {
            return BaseResponse::ResWithStatus($id ? "Có lỗi khi sửa!" : 'Có lỗi xảy ra khi tạo mới!', 500);
        }
    }

    public function submit_quiz(Request $request)
    {
        $id_point = $request->input('id_point');
        $listAnswers = $request->input('listAnswers');

        $data_point = PointSubmit::find($id_point);

        // $now = Carbon::now();
        // if($quiz->deadlines->time_end < $now) {
        //     return BaseResponse::ResWithStatus("Hết thời gian làm bài!", 403);
        // }

        if ($data_point->status == 1) {
            return BaseResponse::ResWithStatus("Bạn đã nộp bài trước đó!", 403);
        }

        $questions = json_decode($data_point->content, true);
        usort($questions, fn ($a, $b) => $a['question_bank_id'] <=> $b['question_bank_id']);
        foreach ($listAnswers as $key => $answer) {
            $questions[$key]['chooses'] = $answer;
        }
        $data_point->content = json_encode($questions);
        $data_point->status = 1;
        # tính điểm
        $point = $this->check_mark($questions);
        $data_point->point = $point;
        $data_point->save();

        return BaseResponse::point("Nộp bài thành công!", $point, 200);
    }

    private function check_mark($list_question)
    {

        $mark = 0;
        $list_id = Arr::pluck($list_question, 'question_bank_id');
        $data_questions = QuestionBank::whereIn('id', $list_id)->get();

        foreach ($data_questions as $data_question) {
            $answers = collect(json_decode($data_question->answers, true))->map(function ($item) {
                if ($item['isCorrect']) {
                    return $item['id'];
                }
            })->toArray();

            # xóa cái element null
            $answers = Arr::flatten(array_filter($answers, fn ($value) => !is_null($value) && $value !== ''));

            # lặp để lấy câu hỏi trùng id để kiểm tra
            foreach ($list_question as $question) {
                # không có câu trả lời thì bỏ qua
                if ($question['chooses'] == []) {
                    continue;
                }
                # trùng id
                if ($question['question_bank_id'] == $data_question->id) {
                    if ($question['chooses'] == $answers) {
                        $mark += 1;
                    }
                }
            }
        }

        return $mark * 10 / count($list_question);
    }

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

        # kiểm tra xem còn trong thời gian deadline hay không
        $now = Carbon::now();
        if ($quiz->deadlines->time_end < $now) {
            return BaseResponse::ResWithStatus("Hết thời gian làm bài!", 403);
        }

        # kiểm tra gần tới deadline mới làm bài (thời gian hiện tại + thời gian làm bài > thời gian deadline)
        if ($now->addSecond($quiz->deadlines->max_time_working) > $quiz->deadlines->time_end) {
            # tính lại thời gian còn lại
            $quiz->deadlines->max_time_working = strtotime($quiz->deadlines->time_end) - strtotime($quiz->deadlines->max_time_working);
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
                ->orderBy('id', 'asc')
                ->inRandomOrder()
                ->take($quiz->deadlines->questions)
                ->get();

            if (count($list_question) < $quiz->deadlines->questions) {
                return BaseResponse::ResWithStatus("Không đủ số lượng câu hỏi vui lòng báo cáo Giảng viên!", 403);
            }

            $course = Course::where('slug', $slug_course)->first();
            # lưu kết quả làm bài của sinh viên
            $new_point = $quiz->point_submit()->create([
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
            $quiz->id_point = $new_point->id;
        } else {
            $now = Carbon::now();
            $point_submit_working = $point_submit[0];
            $re_list_questions = Arr::pluck(json_decode($point_submit_working->content, true), 'question_bank_id');
            # hiển thị lại câu hỏi cũ
            $list_question = QuestionBank::whereIn('id', $re_list_questions)->orderBy('id', 'asc')->get();

            $check_time = $quiz->deadlines->max_time_working - (strtotime($now) - strtotime($point_submit_working->created_at));
            if ($check_time <= 1) {
                # cập nhật trạng thái đã làm xong bài nếu hết thời gian bài đang làm
                $point_submit_working->update([
                    "status" => 1
                ]);
                return BaseResponse::ResWithStatus("Hết thời gian bài đang làm!", 403);
            }
            $quiz->deadlines->max_time_working = $check_time;
            $quiz->id_point = ($point_submit[0])->id;
        }

        return new QuizWorkingResource([
            "data" => [
                'info_quiz' => $quiz,
                'list_questions' => $list_question
            ]
        ]);
    }
}
