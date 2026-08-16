<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendConfirmationEmail implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public function __construct(private Order $order)
    {
    }

    public function handle(): void
    {
        $user = $this->order->user;

        Mail::to($user->email)
            ->send(new OrderConfirmationMail($this->order));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('SendConfirmationEmail failed', [
            'order_id' => $this->order->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
