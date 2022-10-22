<?php

namespace App\Repositories\CourseStudent;

use App\Models\CourseStudent;

class CourseStudentRepository implements CourseStudentInterface
{
    public function addNew($data)
    {
        return CourseStudent::insert($data);
    }

}
