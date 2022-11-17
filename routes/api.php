<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


# người dùng
Route::post('auth/login', [AuthController::class, 'Login'])->name('login');
Route::post('auth/register', [AuthController::class, 'Register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/get-me', [AuthController::class, 'getme']);
    Route::get('user/logout', [AuthController::class, 'logout']);


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
    Route::get('/users', [UserController::class, 'list']);

    Route::prefix('/courses')->group(function () {
        Route::get('/', [CoursesController::class, 'list']);
        Route::post('/new', [CoursesController::class, 'new']);
        Route::get('/get-teacher', [CoursesController::class, 'getTeacher']);
        Route::get('/{id}', [CoursesController::class, 'getById']);
        Route::put('/{id}', [CoursesController::class, 'put']);
        Route::delete('/{id}', [CoursesController::class, 'delete']);
    });

    Route::prefix('/subjects')->group(function () {
        Route::get('/{id}', [SubjectController::class, 'getOne']);
        Route::put('/{id}', [SubjectController::class, 'put']);
        Route::post('/new', [SubjectController::class, 'new']);
        Route::delete('/{id}', [SubjectController::class, 'delete']);
    });

    Route::prefix('/majors')->group(function () {
        // Route::post('/new', [SubjectController::class, 'new']);
        Route::get('/', [MajorController::class, 'list']);
    });

    Route::prefix('user')->group(function () {
        Route::post('/new', [UserController::class, 'new']);

        // Route::get('/', [BranchController::class, '']);
        // Route::get('/get-teacher', [UserController::class, 'getTeacher']);
    });

    Route::prefix('classes')->group(function () {
        Route::get('/', [ClassesController::class, 'list']);

        // Route::get('/', [BranchController::class, '']);
        // Route::get('/get-teacher', [UserController::class, 'getTeacher']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RolesController::class, 'list']);

        // Route::get('/', [BranchController::class, '']);
        // Route::get('/get-teacher', [UserController::class, 'getTeacher']);
    });
});

# cây thư mục
Route::prefix('/branch')->group(function () {
    Route::get('/', [BranchController::class, 'list_parent']);
    // Route::get("{path}", [BranchController::class, 'list_child'])->where('path', '.+');
});

# users
Route::get('/users', [UserController::class, 'list']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
