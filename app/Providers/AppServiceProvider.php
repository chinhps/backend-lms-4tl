<?php

namespace App\Providers;

use App\Models\DocumentGroup;
use App\Models\Major;
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
            'majors' => Major::class,
            'documents_group' => DocumentGroup::class
        ]);
    }
}
