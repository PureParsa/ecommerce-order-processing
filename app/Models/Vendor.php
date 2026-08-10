<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{

    protected $fillable = [
        'name',
        'business_name',
        'email',
        'phone',
        'wallet_balance',
        'payment_processing',
        'locked_at',
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
}
