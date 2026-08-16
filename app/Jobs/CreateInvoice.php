<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;
class CreateInvoice implements ShouldQueue
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
        $invoice = Invoice::create([
            'order_id' => $this->order->id,
            'invoice_number' => 'INV-' . $this->order->id . '-' . now()->format('YmdHis'),
            'amount' => $this->order->total_amount,
            'status' => 'generated'
        ]);
    }
    public function failed(Throwable $exception): void
    {
        Log::error('CreateInvoice failed', ['order_id' => $this->order->id]);
    }
}
