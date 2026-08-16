<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotifyVendor implements ShouldQueue
{
    public function handle(OrderCompleted $event): void
    {
        $vendors = $event->order->vendors;

        foreach ($vendors as $vendor) {
            Log::info('Vendor notified', ['vendor_id' => $vendor->id]);
        }
    }
}
