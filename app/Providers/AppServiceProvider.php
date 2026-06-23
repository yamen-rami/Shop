<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

use App\Models\Catagory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // create a gate to user role 
        // Gate::define("role", function (){
        //     return user()->auth()->role === "user";
        // });
        Model::automaticallyEagerLoadRelationships();
        Paginator::defaultView('vendor.pagination.bootstrap-5');
    }
}
