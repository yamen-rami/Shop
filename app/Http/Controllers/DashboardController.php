<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Company, Contact, Offer, Order, Product, Tag};

class DashboardController extends Controller
{
    //
    public function index(){
        $productCount = Product::count();
        $conatctCount = Contact::count();
        $tagsCount = Tag::count();
        $companiesCount = Company::count();
        $orderCount = Order::count();
        $ActiveOffers = Offer::active()->count();
        $productsOffers = Offer::active()->typeProducts()->count();
        $couponOffers= Offer::active()->coupons()->count();
        $categoryOffers= Offer::active()->catagory()->count();
        return view("dashboard" , [
            "productCount" => $productCount ,
            "conatctCount" => $conatctCount ,
            "tagsCount" => $tagsCount ,
            "companiesCount" => $companiesCount ,
            "orderCount" => $orderCount ,
            "ActiveOffers" => $ActiveOffers ,
            "productsOffers" => $productsOffers ,
            "couponOffers" => $couponOffers ,
            "categoryOffers" => $categoryOffers ,
        ]);

    }
}
