<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;
    protected $guarded = ["id"];
    public function imageable()
    {
        return $this->morphTo();
    }
    public function colors(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id");
    }
}
