<?php

namespace App\Jobs;

use App\Mail\CashoutFailedMail;
use App\Models\VendorCashOut;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendCashoutFailedEmail implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public function __construct(private VendorCashOut $cashOut)
    {
    }

    public function handle(): void
    {
        $user = $this->cashOut->vendor->user;

        Mail::to($user->email)
            ->send(new CashoutFailedMail($this->cashOut));
    }

    public function failed(Throwable $exception): void
    {
        Log::error('SendCashoutFailedEmail failed', [
            'cashout_id' => $this->cashOut->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
