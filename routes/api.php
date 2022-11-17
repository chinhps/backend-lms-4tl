<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CourseJoinedController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

# người dùng
Route::post('auth/login', [AuthController::class, 'Login'])->name('login');
Route::post('auth/register', [AuthController::class, 'Register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/get-me', [AuthController::class, 'getme']);
    Route::get('user/logout', [AuthController::class, 'logout']);

    # cây thư mục
    Route::prefix('/news')->group(function () {
        Route::get('/', [NewsController::class, 'getAll']);
    });

    # cây thư mục
    Route::prefix('/branches')->group(function () {
        Route::get('/', [BranchController::class, 'list_parent']);
    });
    # khóa học
    Route::prefix('/course')->group(function () {

        Route::get('/joined', [CourseJoinedController::class, 'getMyCourse']);
        # tham gia khóa học
        Route::post('/join', [CourseJoinedController::class, 'joinCourse']);
        # thông tin khóa học
        Route::prefix('/{id}')->group(function () {
            Route::get('/', [CoursesController::class, 'getCourseById']);
            Route::get('/quiz', [QuizController::class, 'getQuizSubject']);
            Route::post('/quiz', [QuizController::class, 'saveQuizSubject']);
        });
    });
});


Route::get('/labs', [LabController::class, 'list']);
Route::get('/get-user-message', [MessagesController::class, 'GetMessage']);



Route::get('/create-slug', function () {
    $data = DB::table('courses')->get();
    foreach ($data as $vl) {
        DB::table('courses')->where('id', $vl->id)->update([
            'slug' => Str::slug($vl->name . '-' . $vl->class_code)
        ]);
    }
    return 123;
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
