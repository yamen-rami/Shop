<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\Support\ServiceProvider;
use App\Services\StorefrontData;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(StorefrontData::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FacadesView::composer(["products.index", "home.checkout", "home.wishlist", "products.show", "home.product"], function ($view) {
            $view->with('products_offers', app(StorefrontData::class)->offers());
        });

        FacadesView::composer(['components.cart', 'components.home.navbar', 'components.home.menu'], function ($view) {
            $view->with('cartCount', app(StorefrontData::class)->cartCount());
        });
        FacadesView::composer('components.category', function ($view) {
            $view->with('globalCategories', app(StorefrontData::class)->categories());
        });
        FacadesView::composer(['components.home.navbar', 'components.home.menu'], function ($view) {
            $view->with('favoriatesCount', app(StorefrontData::class)->favoriates());
        });
        FacadesView::composer(["home.home", "home.wishlist", "home.products", "home.checkout" , "home.product"], function ($view) {
            $view->with('globalCart', app(StorefrontData::class)->cart());
        });
        Paginator::defaultView('vendor.pagination.bootstrap-5');
    }
}
