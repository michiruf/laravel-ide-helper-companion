<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::get('/', fn () => 'Hello Workbench!');
        Route::view('/welcome', 'welcome');

        // Include the workbench directory in ide-helper
        config()->set([
            'ide-helper.model_locations' => [
                'app',
                'workbench',
            ],
        ]);
    }
}
