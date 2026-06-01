<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{CartController, CompanyController, HomeController, OfferController, OrderController, ProductController, ProfileController};
use App\Http\Middleware\Checkout;

Route::get('/', [HomeController::class, "home"]); 

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth',"admin",'verified'])->name('dashboard');

Route::middleware(["auth" , "admin"])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource("product" , ProductController::class );
    Route::resource('company', CompanyController::class);
    Route::resource('offer', OfferController::class);

});

Route::resource("order", OrderController::class)->middleware("auth");

Route::get("addCart/{product}" , [CartController::class , "addCart"])->name('addCart')->middleware("auth");
Route::get("home" , [HomeController::class, "home"])->name("home");
Route::get("checkout/" , [CartController::class, "show"])->name("checkout")->middleware(["auth" , Checkout::class]);
Route::delete("cart/destory/{cart}" , [CartController::class, "destroy"])->name("cart.destroy");
require __DIR__.'/auth.php';
