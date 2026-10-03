<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        "desc",
        "image",
        "price",
        "int_price",
        "quantity",
        "featured",
        "original_price",
        'catagory_id'
    ];
    public function companies()
    {
        return $this->belongsToMany(Company::class, "company_product");
    }
    public function offers()
    {
        return $this->belongsToMany(Offer::class, "products_offer");
    }
    public function order()
    {
        return $this->belongsToMany(Order::class, "product_order");
    }
    public function catagory()
    {
        return $this->belongsTo(Catagory::class);
    }

    public function favoriate()
    {
        return $this->hasOne(Favoriate::class);
    }

    public function cart()
    {
        return $this->belongsToMany(Cart::class, 'cart_product');
    }

    public function tag(int $id)
    {
        $this->tags()->attach($id);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, "product_tags");
    }
}
