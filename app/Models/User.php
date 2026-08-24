<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'wallet_balance',
        'vendor_id',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
