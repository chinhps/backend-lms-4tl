<?php

use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessagesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/quiz', [QuizController::class, 'listQuiz']);
Route::post('/quiz', [QuizController::class, 'Quiz']);
Route::get('/labs', [LabController::class, 'list']);
Route::get('/get-user-message', [MessagesController::class, 'GetMessage']);

Route::prefix('/course')->group(function () {
    Route::get('/{id}', [CoursesController::class, 'getCourseById']);
});

Route::prefix('/users')->group(function () {
    Route::get('/list', [UserController::class, 'ListUsers']);
});

Route::prefix('/branch')->group(function () {
    Route::get('/', [BranchController::class, 'list_parent']);
    Route::get("{path}", [BranchController::class, 'list_child'])->where('path', '.+');
});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
