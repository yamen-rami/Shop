<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\{Fillable, Hidden};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Database\Factories\UserFactory;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function order()
    {
        return $this->belongsToMany(Order::class, "user_order");
    }
    public function cart()
    {
        return $this->hasOne(Cart::class);

    }
    public function contacts(){
        return $this->hasMany(Contact::class);
    }
    public function favoriates(){
    return $this->hasMany(Favoriate::class);
  }
}
