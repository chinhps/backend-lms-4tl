<?php

namespace App\Http\Controllers;

use App\Http\Resources\UploadLabResource;
use App\Models\Course;
use App\Models\Lab;
use App\Models\PointSubmit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabController extends Controller
{
    public function joinLab(Request $request)
    {
        $password = $request->input('password') ?? null;
        $slug_lab = $request->input('slug_lab');
        $slug_course = $request->input('slug_course');

        $lab = Lab::with('deadlines', 'labable', 'point_submit')->where('slug', $slug_lab)->first();
        
        if (!isset($lab->deadlines)) {
            return BaseResponse::ResWithStatus("Bài tập này chưa được Giảng viên cấu hình!", 403);
        }

        if ($lab->deadlines->password != null && $password != $lab->deadlines->password) {
            return BaseResponse::ResWithStatus("Mật khẩu sai không thể nộp bài!", 403);
        }

        # kiểm tra xem còn trong thời gian deadline hay không
        $now = Carbon::now();
        if($lab->deadlines->time_end < $now) {
            return BaseResponse::ResWithStatus("Hết thời gian nộp bài!", 403);
        }

        # lấy bài làm của sinh viên đang đăng nhập
        $point_submit = $lab->point_submit()->where([
            'user_id' => Auth::id(),
            'status' => 1
        ])->get();
        
        $count = 0;
        foreach($point_submit as $point) {
            $count += count(json_decode($point->content,true));
        }

        if ($count >= $lab->deadlines->max_working) {
            return BaseResponse::ResWithStatus("Số File quá giới hạn quy định!", 403);
        }

        return new UploadLabResource([
            "data" => [
                'info_lab' => $lab,
            ]
        ]);
    }

    public function submit_lab(Request $request)
    {
        $id_point = $request->input('id_point');
        $slug_course = $request->input('slug_course');
        $slug_lab = $request->input('slug_lab');


        $course = Course::where('slug', $slug_course)->first();
        $lab = Lab::with('deadlines', 'labable', 'point_submit')->where('slug', $slug_lab)->first();

        $data_point = PointSubmit::with('pointsubmitable.deadlines')->find($id_point);
        if(!$data_point) {
            $new_point = $lab->point_submit()->create([
                'user_id' => Auth::id(),
                'course_id' => $course->id ?? 0,
                'content' => '[]',
                'point' => 0,
                'status' => 1, # đã làm
            ]);
            $data_point = PointSubmit::with('pointsubmitable.deadlines')->find($new_point->id);
        }
        # kiểm tra xem số lượng file nộp lên nhiều hơn không
        $check_file = json_decode($data_point->content, true);
        
        if ((count($check_file) + count($request->file('listFile'))) > $data_point->pointsubmitable->deadlines->max_working) {
            return BaseResponse::ResWithStatus("Số lượng bài cũ và mới vượt quá số File được phép nộp!", 403);
        }

        $files = [];
        if ($request->hasfile('listFile')) {

            $content = json_decode($data_point->content,true);
            foreach ($request->file('listFile') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('files'), $name);
                $elmFile = [
                    "name" => $file->getClientOriginalName(),
                    "link" => $name
                ];
                $files[] = $elmFile;
                $content = [...$content,$elmFile];
            }

            $data_point->content = json_encode($content);
            $data_point->save();
            return BaseResponse::ResWithStatus("Nộp bài thành công!", 200);
        }
    }
}
