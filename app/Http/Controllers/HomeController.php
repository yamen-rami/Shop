<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class HomeController extends Controller
{
    //
    public function home(){
        $products = Product::query()->where("quantity" , ">" , 0)->paginate(9);
        return view("home.home" , [
            "products" => $products,
        ]);
    }
}
