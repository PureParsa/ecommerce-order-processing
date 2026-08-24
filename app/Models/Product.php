<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'vendor_id',
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function orderItems()
    {
       return  $this->hasMany(OrderItem::class);
    }
}
