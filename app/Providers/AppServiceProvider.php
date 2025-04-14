<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Http\Kernel;
use App\Http\Middleware\DebugMiddleware;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Layouts\Client;
use App\View\Components\Layouts\Writer;
use App\View\Components\Layouts\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // No special resolving needed
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Kernel $kernel): void
    {
        // Register layout components with a single convention
        Blade::component('admin-layout', Admin::class);
        Blade::component('client-layout', Client::class);
        Blade::component('layout-writer', Writer::class);
    }
}
