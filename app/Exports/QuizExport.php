<?php

namespace App\Exports;

use App\Models\PointSubmit;
use Maatwebsite\Excel\Concerns\FromCollection;

class QuizExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PointSubmit::get();
    }
}
