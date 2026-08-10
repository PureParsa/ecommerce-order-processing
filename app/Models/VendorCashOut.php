<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCashOut extends Model
{
    protected $fillable = [
        'vendor_id',
        'amount',
        'status',
        'reference_number',
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}

