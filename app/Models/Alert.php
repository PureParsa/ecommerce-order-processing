<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'type',
        'order_id',
        'message',
        'resolved',
    ];
    protected function casts(): array
    {
        return [
            'resolved' => 'boolean',
        ];
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
