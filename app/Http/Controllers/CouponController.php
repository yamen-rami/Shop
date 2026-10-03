<?php

namespace App\Http\Controllers;

class CouponController extends Controller
{
    public function index()
    {
        return view('coupons.index');
    }

    public function catagory()
    {
        return view('catagory_offer.index');
    }
}
