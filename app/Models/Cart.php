<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder as EloquentBuilder, Model};

class Cart extends Model
{
    
    protected $fillable = ["user_id"];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, "cart_product")

            ->withPivot("quantity")->withTimestamps();
    }
    public function scopeValid(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where("user_id", auth()->id())->where("created_at", ">", now()->subDay());
    }
}
