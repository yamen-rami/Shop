<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use App\Models\Offer; // 👈 Double-check that this path is correct for your project!

class Product extends Model
{
  /** @use HasFactory<\Database\Factories\ProductFactory> */
  use HasFactory;
  protected $fillable = [
    'name',
    "desc",
    "image",
    "price",
    "int_price",
    "quantity",
    "original_price",
    'catagory_id'
  ];

  // appends For discount_price
  protected $appends = [
    'discount_price',
    "has_discount"
  ];
  // Company Relation
  public function companies()
  {
    return $this->belongsToMany(Company::class, "company_product");
  }
  // Order Relation
  public function order()
  {
    return $this->belongsToMany(Order::class, "product_order");
  }
  // Catagory Relation
  public function catagory()
  {
    return $this->belongsTo(Catagory::class);
  }
  public function favoriate(){
    return $this->hasOne(Favoriate::class);
  }

  public function cart()
  {
    return $this->belongsToMany(Cart::class, 'cart_product');
  }
  // apply creating an tag
  public function tag(int $id)
  {
    $this->tags()->attach($id);
  }
  
  public function tags()
  {
    return $this->belongsToMany(Tag::class, "product_tags");
  }
  public function catagoryOffer()
  {
    return $this->hasOne(Offer::class, "catagory_id", "catagory_id");
  }
  public function globalOffer()
  {
    return $this->hasOne(Offer::class, "catagory_id", "catagory_id")->global()->active();
  }
  // ! apply discount For All The Products
  protected function discountPrice(): Attribute
  {
    return Attribute::make(
      get: function () {
        $best = $this->price;
        $now = Carbon::now();
        if ($this->catagory_id) {
          if ($this->catagoryOffer) {
            $best =  $this->calculateDiscount($this->price, $this->catagoryOffer);
          }
        }
        // $globalOffer = Offer::global()->active()->whereNull("catagory_id")->first();

        if ($this->globalOffer) {
          if ($best > $this->calculateDiscount($this->price, $this->globalOffer)) {
            $best = $this->calculateDiscount($this->price, $this->globalOffer);
          }
        }
        return $best;
      }
    );
  }
  private function calculateDiscount(float $price, Offer $offer)
  {
    if ($offer->discount_type === 'percentage') {
      return  $price * (1 - $offer->discount_value);
    }
    if ($offer->discount_type === 'fixed_amount') {
      return  $price - $offer->discount_value;
    }
    return $price;
  }
  protected function hasDiscount(): Attribute
  {
    return Attribute::make(
      get: fn() => $this->discount_price < $this->price
    );
  }
}
