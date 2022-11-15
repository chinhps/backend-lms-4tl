<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


# người dùng
Route::post('auth/login',[AuthController::class,'Login'])->name('login');
Route::post('auth/register',[AuthController::class,'Register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/get-me',[AuthController::class,'getme']);
    Route::get('user/logout',[AuthController::class,'logout']);
});


Route::get('/labs', [LabController::class, 'list']);
Route::get('/get-user-message', [MessagesController::class, 'GetMessage']);

# khóa học
Route::prefix('/course')->group(function () {
    # tham gia khóa học
    Route::post('/join/{id}', [CourseStudentController::class, 'addStudent_to_Course']);
    # thông tin khóa học
    Route::prefix('/{id}')->group(function () {
        Route::get('/', [CoursesController::class, 'getCourseById']);
        Route::get('/quiz', [QuizController::class, 'getQuizSubject']);
        Route::post('/quiz', [QuizController::class, 'saveQuizSubject']);
    });
});

# cây thư mục
Route::prefix('/branch')->group(function () {
    Route::get('/', [BranchController::class, 'list_parent']);
    Route::get("{path}", [BranchController::class, 'list_child'])->where('path', '.+');
});

# acac
Route::post('/users', [UserController::class, 'list']);
Route::put('/users/{id}', [UserController::class, 'list']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
