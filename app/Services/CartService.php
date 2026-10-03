<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Models\{Offer, Product};
use App\Services\OfferService;

class CartService
{
  public  $offers;
  public function __construct(protected OfferService $offerService)
  {
    $this->offers = $offerService;
  }
  public function totalPrice(Collection $products, Collection $offers , ?string $code = null)
  {
    return $products->sum(function ($product) use ($offers , $code) {
      return $product->pivot->quantity *  $this->offers->getCoupon($product, $offers , $code)["best"];
    });
  }
  public function originalPrice(Collection $products )
  {
    return $products->sum(function ($product) {
      return $product->pivot->quantity * $product->price;
    });
  }
  public function discountTotal(Collection $products, Collection $offer , ?string $code = null)
  {
    return $products->sum(
      function ($product) use($offer , $code) {
        return $product->pivot->quantity * ($product->price - $this->offers->getCoupon($product, $offer , $code)["best"]);
      }
    );
  }
}
