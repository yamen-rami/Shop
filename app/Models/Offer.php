<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder as EloquentBuilder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;

class Offer extends Model
{
    /** @use HasFactory<\Database\Factories\OfferFactory> */
    use HasFactory;
    protected $fillable = [
        "name",
        "code",
        "discount_type",
        "discount_value",
        "start_date",
        "end_date",
        "catagory_id",
        "is_active",
        "type"
    ];
    public function products(){
        return $this->belongsToMany(Product::class,"products_offer");
    }
    public function categories()
    {
        return $this->belongsToMany(Catagory::class, "categories_offer");
    }
    public function scopeCoupons(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where('type' , "coupon");
    }
    public function scopeTypeProducts(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where('type' , "products");
    }
    public function scopeGlobal(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where("type","global");
    }
    public function scopeCatagory(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where("type" , "categories");
    }
    public function scopeActive($query)
    {
        $now = now();
        return $query->where("is_active", true)->where(function ($query) use ($now) {
            $query->where("start_date", "<=", $now);
        })->where(function ($query) use ($now) {
            $query->where("end_date", ">=", $now);
        });
    }
}
