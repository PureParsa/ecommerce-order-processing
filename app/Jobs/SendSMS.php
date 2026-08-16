<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendSMS implements ShouldQueue
{
    use Queueable;
    public $tries = 3;
    public $backoff = [10, 30, 60];
    /**
     * Create a new job instance.
     */
    public function __construct(private Order $order)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('SMS sent to ' . $this->order->user->phone, [
            'order_id' => $this->order->id,
            'message' => 'Order confirmed'
        ]);
    }
    public function failed(Throwable $exception): void
    {
        Log::error('SendSMS failed', [
            'order_id' => $this->order->id,
            'phone' => $this->order->user->phone,
            'error' => $exception->getMessage()
        ]);
    }
}
