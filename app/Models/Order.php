<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_gateway_ref',
        'last_failed_job',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
    public function vendors()
    {
        return $this->hasManyThrough(
            Vendor::class,
            OrderItem::class,
            'order_id',
            'id',
            'id',
            'vendor_id'
        );
    }
}
