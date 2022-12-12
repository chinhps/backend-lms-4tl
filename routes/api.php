<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseJoinedController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\DeadLineController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\{
    QuizController, 
    NewsController, 
    QuestionBankController, 
    RolesController, 
    LabController
};
use App\Http\Controllers\LoginWith\LoginGoogleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PermissionGroupController;
use App\Http\Controllers\PointSubmitController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Models\PointSubmit;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


# người dùng
Route::post('auth/login', [AuthController::class, 'Login'])->name('login');
Route::post('auth/register', [AuthController::class, 'Register']);
Route::get('auth/callback', [LoginGoogleController::class, 'callback']);
Route::post('auth/get-google-sign-in-url', [LoginGoogleController::class, 'getGoogleSignInUrl']);
Broadcast::routes(['middleware' => ['auth:sanctum']]);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/get-me', [AuthController::class, 'getme']);
    Route::get('user/logout', [AuthController::class, 'logout']);
    Route::put('user/change-password', [AuthController::class, 'change_password']);

    # chat
    Route::prefix('/chat')->group(function () {
        Route::post('/my-room', [ChatController::class, 'my_room']);
        Route::post('/send', [ChatController::class, 'send']);
        Route::get('/{slug}', [ChatController::class, 'list_message']);
    });

    # cây thư mục
    Route::prefix('/news')->group(function () {
        Route::get('/', [NewsController::class, 'getAll']);
        Route::get('/{id}', [NewsController::class, 'getOne']);

        Route::post('/new', [NewsController::class, 'new']);
    });

    # cây thư mục
    Route::prefix('/branches')->group(function () {
        Route::get('/', [BranchController::class, 'list_parent']);
    });

    # khóa học
    Route::prefix('/course')->group(function () {

        Route::prefix('/students')->group(function () {
            Route::get('/{slug}', [CourseJoinedController::class, 'getListByCourse']);
        });

        Route::prefix('/quiz')->group(function () {
            # lấy bài quiz
            Route::post('/', [QuizController::class, 'joinQuiz']);
            Route::get('/{slug}', [QuizController::class, 'getOne']);
            # xóa
            Route::delete('/{slug}', [QuizController::class, 'delete']);
            # nộp bài quiz 
            Route::post('/done', [QuizController::class, 'submit_quiz']);
            # tạo mới
            Route::post('/create', [QuizController::class, 'upsert']);
        });

        Route::prefix('/lab')->group(function () {
            # lấy bài lab
            Route::post('/', [LabController::class, 'joinLab']);
            Route::get('/{slug}', [LabController::class, 'getOne']);
            # xóa
            Route::delete('/{slug}', [LabController::class, 'delete']);
            # nộp bài lab 
            Route::post('/done', [LabController::class, 'submit_lab']);
            # tạo mới
            Route::post('/create', [LabController::class, 'upsert']);
        });

        Route::prefix('/document')->group(function () {
            Route::get('/{slug}', [DocumentController::class, 'getOne']);
            # xóa
            Route::delete('/{slug}', [DocumentController::class, 'delete']);
            # tạo mới
            Route::post('/create', [DocumentController::class, 'upsert']);
        });

        Route::prefix('/deadline')->group(function () {
            Route::get('/{type}/{slug}', [DeadLineController::class, 'getOne']);
            Route::post('/create', [DeadLineController::class, 'upsert']);
        });

        Route::prefix('/point-submits')->group(function () {
            Route::get('/list/{type}/{slug}', [PointSubmitController::class, 'getListSlug']);
            # chấm điểm
            Route::get('/{id}', [PointSubmitController::class, 'getOneFormat']);
            Route::post('/mark', [PointSubmitController::class, 'mark']);
            Route::get('/export/{type}', [PointSubmitController::class, 'export']);
        });

        Route::get('/joined', [CourseJoinedController::class, 'getMyCourse']);
        # tham gia khóa học
        Route::post('/join', [CourseJoinedController::class, 'joinCourse']);
        # thông tin khóa học
        Route::get('/{slug}', [CoursesController::class, 'showDocQuizLab']);
    });



    Route::get('/labs', [LabController::class, 'list']);

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
        Route::get('/fulllist', [ClassesController::class, 'listFull']);
        Route::get('/', [ClassesController::class, 'list']);
        Route::delete('/{id}', [ClassesController::class, 'delete']);
        Route::post('/new', [ClassesController::class, 'new']);
        Route::get('/{id}', [ClassesController::class, 'getOne']);
        Route::put('/{id}', [ClassesController::class, 'put']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/fulllist', [RolesController::class, 'listFull']);
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

    Route::prefix('point-submit')->group(function () {
        Route::get('/', [PointSubmitController::class, 'list']);
        Route::post('/new', [PointSubmitController::class, 'new']);
        Route::get('/{id}', [PointSubmitController::class, 'getOne']);
        Route::put('/{id}', [PointSubmitController::class, 'put']);
        Route::delete('/{id}', [PointSubmitController::class, 'delete']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'dashboard']);
    });
});


Route::get('/create-slug', function () {
    $data = DB::table('documents')->get();
    foreach ($data as $vl) {
        DB::table('documents')->where('id', $vl->id)->update([
            'slug' => Str::slug($vl->name . '-' . Str::random(4))
        ]);
    }
    return 123;
    // return Hash::make(123456789);
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });