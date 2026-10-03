<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\{DB, Gate, Log, View as FacadesView};
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

use App\Models\{Cart, Catagory, Offer};
use Pest\Support\View;

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
        FacadesView::composer(["products.index" , "home.checkout", "home.wishlist" ,"home.checkout" ,"home.offers" , "products.show" , "home.product"], function ($view) {
            $products_offers = Offer::with(["products" , "categories"])->active()->get();
            $view->with("products_offers", $products_offers);
        });
        FacadesView::composer("*", function ($view) {
            if (auth()->check()) {
                $cartCount = once(function () {
                    $cart = Cart::with("products")->valid()->first();
                    return $cart ? $cart->products->sum("pivot.quantity") : 0;
                });
                $view->with("cartCount", $cartCount);
            } else {
                $view->with("cartCount", 0);
            }
        });
        FacadesView::composer('*', function ($view) {
            // Fetch categories once per request with all nested relationships pre-loaded
            $globalCategories = once(function () {
                return Catagory::with(["products"])->limit(10)
                    ->get();
            });

            $view->with('globalCategories', $globalCategories);
        });
        FacadesView::composer(["home.home", "home.wishlist", "home.products", "home.checkout" , "home.product"], function ($view) {
            if (auth()->check()) {
                // "once" ensures this query runs EXACTLY once per page load
                $globalCart = once(function () {
                    return Cart::with(["products"])->where("user_id" , auth()->id())->valid()->first();
                });
                $view->with('globalCart', $globalCart);
            } else {
                $view->with('globalCart', null);
            }
        });
        Paginator::defaultView('vendor.pagination.bootstrap-5');
    }
}
