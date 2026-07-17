<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComponentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register Blade Components
        \Blade::component('input', \App\View\Components\Input::class);
        \Blade::component('button', \App\View\Components\Button::class);
        \Blade::component('alert', \App\View\Components\Alert::class);
        \Blade::component('pagination', \App\View\Components\Pagination::class);
    }

    public function register(): void
    {
        //
    }
}