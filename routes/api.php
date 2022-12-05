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
use App\Http\Controllers\QuestionBankController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\LoginWith\LoginGoogleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PermissionGroupController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

# người dùng
Route::post('auth/login', [AuthController::class, 'Login'])->name('login');
Route::post('auth/register', [AuthController::class, 'Register']);
Route::get('auth/callback', [LoginGoogleController::class, 'callback']);
Route::post('auth/get-google-sign-in-url', [LoginGoogleController::class, 'getGoogleSignInUrl']);

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

        # lấy bài quiz
        Route::get('/quiz', [QuizController::class, 'getQuizCourse']);

        Route::get('/joined', [CourseJoinedController::class, 'getMyCourse']);
        # tham gia khóa học
        Route::post('/join', [CourseJoinedController::class, 'joinCourse']);
        # thông tin khóa học
        Route::get('/{slug}', [CoursesController::class, 'showDocQuizLab']);
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
        Route::get('/fulllist', [SubjectController::class, 'listFull']);
        Route::get('/', [SubjectController::class, 'list']);
        Route::get('/{id}', [SubjectController::class, 'getOne']);
        Route::put('/{id}', [SubjectController::class, 'put']);
        Route::post('/new', [SubjectController::class, 'new']);
        Route::delete('/{id}', [SubjectController::class, 'delete']);
    });

    Route::prefix('/majors')->group(function () {
        Route::post('/new', [MajorController::class, 'new']);
        Route::get('/', [MajorController::class, 'list']);
        Route::get('/{id}', [MajorController::class, 'getById']);
        Route::put('/{id}', [MajorController::class, 'put']);
        Route::delete('/{id}', [MajorController::class, 'delete']);
    });

    Route::prefix('users')->group(function () {
        Route::post('/new', [UserController::class, 'new']);
        Route::get('/{id}', [UserController::class, 'getOne']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete']);

        // Route::get('/', [BranchController::class, '']);
        // Route::get('/get-teacher', [UserController::class, 'getTeacher']);
    });

    Route::prefix('classes')->group(function () {
        Route::get('/', [ClassesController::class, 'list']);
        Route::delete('/{id}', [ClassesController::class, 'delete']);
        Route::post('/new', [ClassesController::class, 'new']);
        Route::get('/{id}', [ClassesController::class, 'getOne']);
        Route::put('/{id}', [ClassesController::class, 'put']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RolesController::class, 'list']);
        Route::get('/{id}', [RolesController::class, 'getOne']);
        Route::put('/{id}', [RolesController::class, 'put']);
        Route::delete('/{id}', [RolesController::class, 'delete']);
        Route::post('/new', [RolesController::class, 'new']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'list']);
        Route::get('/{id}', [PermissionController::class, 'getOne']);
        Route::put('/{id}', [PermissionController::class, 'put']);
        Route::delete('/{id}', [PermissionController::class, 'delete']);
        Route::post('/new', [PermissionController::class, 'new']);
    });

    Route::prefix('permission-groups')->group(function () {
        Route::get('/fulllist', [PermissionGroupController::class, 'listFull']);
        Route::get('/', [PermissionGroupController::class, 'list']);
        Route::get('/{id}', [PermissionGroupController::class, 'getOne']);
        Route::put('/{id}', [PermissionGroupController::class, 'put']);
        Route::delete('/{id}', [PermissionGroupController::class, 'delete']);
        Route::post('/new', [PermissionGroupController::class, 'new']);
    });

    Route::prefix('question_bank')->group(function () {
        Route::get('/', [QuestionBankController::class, 'list']);
        Route::post('/new', [QuestionBankController::class, 'new']);
        Route::get('/{id}', [QuestionBankController::class, 'getOne']);
        Route::put('/{id}', [QuestionBankController::class, 'put']);
        Route::delete('/{id}', [QuestionBankController::class, 'delete']);
    });
});


Route::get('/create-slug', function () {
    $data = DB::table('quizs')->get();
    foreach ($data as $vl) {
        DB::table('quizs')->where('id', $vl->id)->update([
            'slug' => Str::slug($vl->name . '-' . Str::random(4))
        ]);
    }
    return 123;
    // return Hash::make(123456789);
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });