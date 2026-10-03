<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{CartController, CatagoryController, CompanyController, ContactController, CouponController, DashboardController, HomeController, LocaleController, OfferController, OrderController, ProductController, ProfileController, TagController};
use App\Http\Middleware\{AdminCheck, Checkout};

Route::get('/select-options/{resource}', [\App\Http\Controllers\SelectOptionsController::class, 'index'])
    ->whereIn('resource', ['categories', 'companies', 'products', 'tags'])->name('select-options');

Route::get('/', [HomeController::class, "home"]);
// ? Localization
Route::get("locale/{lang}", [LocaleController::class, "setLocale"]);

Route::get('/dashboard', [DashboardController::class , "index"])->middleware(['auth', "admin", 'verified'])->name('dashboard');
// Admin Middleware
Route::middleware(["auth", "admin"])->group(function () {
    Route::get("offerCoupons", [CouponController::class, "index"])->name("offerCoupons");
    Route::get("catagoryOffers", [CouponController::class, "catagory"])->name("catagoryOffers");
    Route::resource("product", ProductController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('offer', OfferController::class);
    Route::resource('tag', TagController::class)->except('show');
    Route::get("productsOffer", [OfferController::class, 'productsOffer'])->name("productsOffer");
    Route::resource('catagory', CatagoryController::class);
    Route::get("catagories/products/{catagory}", [ProductController::class, "catagoryProducts"])->name('getProducts');
    Route::delete("tag/delete/{tag}", [TagController::class, "destroy"])->name("tag.delete");
});
// TODO Auth Routes
Route::middleware("auth")->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('contact', ContactController::class);
    Route::resource("order", OrderController::class);
    Route::post("addCart/{product}", [CartController::class, "addCart"])->name('addCart');
    Route::get("/checkout", [CartController::class, "show"])->name("checkout")->middleware(Checkout::class);
    Route::delete("cart/destory/{product}", [CartController::class, "destroy"])->name("cart.destroy");
    Route::get("home.wishlist", [HomeController::class, "wishlist"])->name("wishlist");
    Route::delete("deleteCartItem/{id}", [CartController::class, "delete"])->name("deleteCartItem");
});
Route::get("home", [HomeController::class, "home"])->name("home");
Route::get("products", [HomeController::class, "products"])->name("products");
Route::get("home/product/{product}", [HomeController::class, "showProduct"])->name("showProduct");
Route::redirect("home/catgory", "/products")->name("categories");
Route::get("home/offers", [HomeController::class, "offers"])->name("home.offers");
// Route::resource("home/tags", HomeTagController::class);
Route::fallback([HomeController::class, "notFound"]);

require __DIR__ . '/auth.php';
