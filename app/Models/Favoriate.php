<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favoriate extends Model
{
    /** @use HasFactory<\Database\Factories\FavoriateFactory> */
    use HasFactory;
    protected $fillable = ["user_id" , "product_id"];

    public function user(){
        return $this->belongsTo(User::class);
    }
    // Product Class 
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
