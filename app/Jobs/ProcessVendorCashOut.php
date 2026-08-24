<?php

namespace App\Jobs;

use App\Models\VendorCashOut;
use App\Services\CashOutService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessVendorCashOut implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private VendorCashOut $cashOut)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(CashOutService $cashOutService): void
    {
        // Simulate sending the cashout to the banking department.
        $successful = true;

        if ($successful) {
            $cashOutService->completeCashOut(
                $this->cashOut,
                'BANK-' . fake()->unique()->numberBetween(100000, 999999)
            );

            return;
        }

        $cashOutService->failCashOut($this->cashOut);
    }
}
