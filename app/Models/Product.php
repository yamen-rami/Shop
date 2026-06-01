<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $fillable = [
        'name' , 
        "desc" ,
        "image" , 
        "price" , 
        "int_price" , 
        "quantity"
    ];

    public function companies(){
        return $this->belongsToMany(Company::class , "company_product");
    }
    public function order(){
        return $this->belongsToMany(Order::class , "product_order");
    }
    public function cart(){
        return $this->belongsToMany(Cart::class, 'cart_product');
    }
}
