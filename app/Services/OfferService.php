<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

use App\Models\{Offer, Product};
use Mockery\QuickDefinitionsConfiguration;

class OfferService
{

  public function getDiscount(Product $product, EloquentCollection $offers)
  {
    $applicableOffers = $offers->filter(function ($offer) use ($product) {
      if ($offer->type === 'global') {
        return true;
      }
      if ($offer->type === 'categories') {
        return $offer->categories->contains("id", $product->catagory_id);
      }
      if ($offer->type === "products") {
        return $offer->products->contains("id", $product->id);
      }
      return false;
    });
    $best = $product->price;
    $outOffer = null;
    foreach ($applicableOffers as $offer) {
      $price = $this->discountPrice($product, $offer);
      if ($price < $best) {
        $best = $price;
        $outOffer = $offer;
      }
    }
    return ["best" => $best, "offer" => $outOffer];
  }
  /*
    1- get the current Best 
    2- compare it to the current coupon offer 
    3- get the best value for the customer
   */
  public function applyCoupon(EloquentCollection $offers, ?string $code = null)
  {
    if (!auth()->check()) {
      return redirect()->route("login");
    }
    $products = auth()->user()->cart->products;
    foreach ($products as $product) {
      $this->getCoupon($product, $offers, $code);
    }
  }

  public function getCoupon(Product $product, EloquentCollection $offers, ?string $code = null)
  {
    if (!blank($code)) {
      $couponOffer = Offer::where("code", $code)->active()->latest()->first();
      if (!empty($couponOffer)) {

        $couponResult = $this->discountPrice($product, $couponOffer);
        $offersResult = $this->getDiscount($product, $offers);

        if ($couponResult <= $offersResult["best"]) {
          return [
            "best" => $couponResult,
            "offer" => $couponOffer,
          ];
        }
      }
    }
    return [
      "best" =>  $this->getDiscount($product, $offers)["best"],
      "offer" => $this->getDiscount($product, $offers)["offer"],
    ];
  }

  /* 
    Best Offer
  */


  public function discountPrice(Product $product, Offer $offer)
  {
    if ($product && $offer) {

      if ($offer->discount_type === "percentage") {
        return $product->price * (1 - $offer->discount_value);
      }
      return max(0, $product->price - $offer->discount_value);
    }
  }

  public function offerType(?Offer $offer = null)
  {
   if(!$offer){
    return ;
   }
    if ($offer->discount_type === "percentage") {
      return "%" . $offer->discount_value * 100;
    }
    return "$" . $offer->discount_value;
  }
}
