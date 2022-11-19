<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseJoinedController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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



    Route::get('/labs', [LabController::class, 'list']);
    Route::get('/get-user-message', [MessagesController::class, 'GetMessage']);

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
        Route::get('/', [SubjectController::class, 'list']);
        Route::get('/{id}', [SubjectController::class, 'getOne']);
        Route::put('/{id}', [SubjectController::class, 'put']);
        Route::post('/new', [SubjectController::class, 'new']);
        Route::delete('/{id}', [SubjectController::class, 'delete']);
    });

    Route::prefix('/majors')->group(function () {
        // Route::post('/new', [SubjectController::class, 'new']);
        Route::get('/', [MajorController::class, 'list']);
    });

    Route::prefix('users')->group(function () {
        Route::post('/new', [UserController::class, 'new']);
        Route::get('/{id}', [UserController::class, 'getOne']);
        Route::put('/{id}', [UserController::class, 'update']);
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


Route::get('/create-slug', function () {
    // $data = DB::table('courses')->get();
    // foreach ($data as $vl) {
    //     DB::table('courses')->where('id', $vl->id)->update([
    //         'slug' => Str::slug($vl->name . '-' . $vl->class_code)
    //     ]);
    // }
    return Hash::make(123456789);
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });