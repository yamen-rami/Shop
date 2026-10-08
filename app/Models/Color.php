<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Color extends Model
{
    /** @use HasFactory<\Database\Factories\ColorFactory> */
    use HasFactory;
    protected $fillable = ["name"];
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function colors(): HasMany
    {
        return $this->images();
    }
}
