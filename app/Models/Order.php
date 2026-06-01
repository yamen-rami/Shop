<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = ["name" , "location" , "quantity" , "price"];
    public function products(){
        return $this->belongsToMany(Product::class , "product_order"); 
    }
    public function user(){
        return $this->BelongsToMany(User::class , "user_order");
    }
}
