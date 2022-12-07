<?php

namespace App\Http\Controllers;

use App\Http\Resources\UploadLabResource;
use App\Models\Lab;
use App\Models\PointSubmit;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function joinLab(Request $request)
    {
        $password = $request->input('password') ?? null;
        $slug_lab = $request->input('slug_lab');
        $slug_course = $request->input('slug_course');

        $lab = Lab::with('deadlines', 'labable', 'point_submit')->where('slug', $slug_lab)->first();
        return new UploadLabResource([
            "data" => [
                'info_lab' => $lab,
            ]
        ]);
    }

    public function submit_lab(Request $request)
    {
        $id_point = $request->input('id_point');

        $data_point = PointSubmit::with('pointsubmitable.deadlines')->find($id_point);

        # kiểm tra xem số lượng file nộp lên nhiều hơn không
        $check_file = json_decode($data_point->content, true);
      
        if ((count($check_file) + count($request->file('listFile'))) > $data_point->pointsubmitable->deadlines->max_working) {
            return BaseResponse::ResWithStatus("Số lượng bài cũ và mới vượt quá số File được phép nộp!", 403);
        }

        $files = [];
        if ($request->hasfile('listFile')) {
            foreach ($request->file('listFile') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('files'), $name);
                $files[] = [
                    "name" => $file->getClientOriginalName(),
                    "link" => $name
                ];
            }
            $data_point->content = json_encode($files);
            $data_point->save();
            return BaseResponse::ResWithStatus("Nộp bài thành công!", 200);
        }
    }
}
