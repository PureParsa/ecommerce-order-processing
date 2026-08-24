<?php

namespace App\Jobs;

use App\Models\VendorCashOut;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendCashoutFailedSMS implements ShouldQueue
{
    use Queueable;
    public $tries = 3;
    public $backoff = [10, 30, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(private VendorCashOut $cashOut)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('SMS sent to ' . $this->cashOut->vendor->phone, [
            'cashout_id' => $this->cashOut->id,
            'message' => 'Cashout failed'
        ]);
    }
    public function failed(Throwable $exception): void
    {
        Log::error('SendSMS failed', [
            'cashout_id' => $this->cashOut->id,
            'phone' => $this->cashOut->vendor->phone,
            'error' => $exception->getMessage()
        ]);
    }
}
