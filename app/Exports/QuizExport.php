<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\PointSubmit;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class QuizExport implements FromView
{
    public function __construct(public $res)
    {
    }
    public function view(): View
    {
        return view('exports.quizByCourse',$this->res);
    }
}
