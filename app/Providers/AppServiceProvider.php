<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        array_map(function ($repoFile) {
            $modelClassName = basename($repoFile, 'Repository.php');
            $repositoryClass = '\\App\\Repositories\\' . $modelClassName . 'Repository';
            $modelClass = '\\App\\Models\\' . $modelClassName;
            if (class_exists($modelClass)) {
                $modelClass::observe($repositoryClass);
            }
        }, glob(app_path('Repositories/*.php')));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
