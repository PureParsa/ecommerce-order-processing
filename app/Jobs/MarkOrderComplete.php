<?php

namespace App\Jobs;

use App\Events\OrderCompleted;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;
class MarkOrderComplete implements ShouldQueue
{
    use Queueable;

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
        $this->order->update(['status' => 'completed']);
        OrderCompleted::dispatch($this->order);
    }
    public function failed(Throwable $exception): void
    {
        Log::critical('MarkOrderComplete failed', [
            'order_id' => $this->order->id,
            'error' => $exception->getMessage()
        ]);
    }
}
