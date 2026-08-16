<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateVendorBalance
{
    /**
     * Create the event listener.
     */

    /**
     * Handle the event.
     */
    public function handle(OrderCompleted $event): void
    {
        $vendors = $event->order->vendors;
        foreach ($vendors as $vendor) {
            $itemsTotal = $vendor->orderItems()
                ->where('order_id', $event->order->id)
                ->sum('price');

            $vendorShare = $itemsTotal * 0.9;
            $vendor->increment('wallet_balance', $vendorShare);
        }
        Log::info('Vendor balance updated', [
            'vendor_id' => $vendor->id,
            'amount' => $vendorShare
        ]);
    }
}
