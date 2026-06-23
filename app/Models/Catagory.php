<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catagory extends Model
{
    /** @use HasFactory<\Database\Factories\CatagoryFactory> */
    use HasFactory;
    protected $fillable = ["name" ,"desc"];
    public function products(){
       return $this->hasMany(Product::class); 
    }
    public function offer(){
        return $this->hasOne(Offer::class);
    }
}
