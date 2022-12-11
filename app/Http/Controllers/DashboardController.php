<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $classes = DB::table('classes')->count();
        $courses = DB::table('courses')->count();
        $documents = DB::table('documents')->count();
        $labs = DB::table('labs')->count();
        $majors = DB::table('majors')->count();
        $news = DB::table('news')->count();
        $permissions = DB::table('permissions')->count();
        $question_bank = DB::table('question_bank')->count();
        $quizs = DB::table('quizs')->count();
        $role = DB::table('role')->count();
        $subjects = DB::table('subjects')->count();
        $users = DB::table('users')->count();


        return response()->json(['classes' => $classes, 'courses' => $courses, 'documents' => $documents, 'labs' => $labs, 'majors' => $majors, 'news' => $news, 'permissions' => $permissions, 'question_bank' => $question_bank, 'quizs' => $quizs, 'role' => $role, 'subjects' => $subjects, 'users' => $users]);
    }
}
