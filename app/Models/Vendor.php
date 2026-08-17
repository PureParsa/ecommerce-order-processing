<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
use hasfactory;
    protected $fillable = [
        'business_name',
        'email',
        'phone',
        'wallet_balance',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'payment_processing' => 'boolean',
            'locked_at' => 'datetime',
        ];
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function vendorCashOuts()
    {
        return $this->hasMany(VendorCashOut::class);
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
