<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\PointSubmit;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class QuizExport implements FromView
{
    public function __construct(public $type, public $slug)
    {
    }
    public function view(): View
    {
        $type = $this->type;
        $slug = $this->slug;

        $quizs = Course::with([
            'point_submits' => function ($query) use ($type) {
                $query->where('pointsubmitable_type', $type)->orderBy('id', 'desc')
                    ->groupBy('user_id', 'pointsubmitable_id')->select('*', DB::raw('count(*) as total'))->get();
            }, 'point_submits.user', 'point_submits.pointsubmitable'
        ])->where('slug', $slug)->first();

        return $quizs;

        return view('exports.quizByCourse', [
            'quizs' => $quizs
        ]);
    }
}
