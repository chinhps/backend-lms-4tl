<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentGroup;
use App\Models\Lab;
use App\Models\Major;
use App\Models\Quiz;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'courses' => Course::class,
            'labs' => Lab::class,
            'quizs' => Quiz::class,
            'subjects' => Subject::class,
            'documents' => Document::class,
            'majors' => Major::class,
            'documents_group' => DocumentGroup::class
        ]);
    }
}
