<?php
namespace App\Services;

use App\Exceptions\InsufficientWalletBalanceException;
use App\Jobs\ProcessVendorCashOut;
use App\Jobs\SendCashoutConfirmationEmail;
use App\Jobs\SendCashoutFailedEmail;
use App\Jobs\SendCashoutConfirmationSMS;
use App\Jobs\SendCashoutFailedSMS;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorCashOut;
use Illuminate\Support\Facades\DB;
class CashOutService{

    public function cashOut(array $data , User $user)
    {
       $cashOut =  DB::transaction(function() use ($user , $data){
            $vendor = Vendor::lockForUpdate()
                ->findOrFail($user->vendor_id);
          if($data['amount']>$vendor->wallet_balance)
          {
              throw new InsufficientWalletBalanceException();
          }
            $cashOut = VendorCashOut::create([
                'vendor_id' => $vendor->id,
                'amount' => $data['amount'],
                'status' => 'pending'
            ]);
            $vendor->decrement('wallet_balance', $data['amount']);
           return $cashOut;
       });
        ProcessVendorCashOut::dispatch($cashOut);
        return $cashOut;
    }

    public function completeCashOut(VendorCashOut $cashOut, string $referenceNumber): VendorCashOut {
        return DB::transaction(function () use ($cashOut, $referenceNumber) {

            $cashOut = VendorCashOut::lockForUpdate()
                ->findOrFail($cashOut->id);

            if ($cashOut->status !== 'pending') {
                return $cashOut;
            }

            $cashOut->update([
                'status' => 'completed',
                'reference_number' => $referenceNumber,
            ]);
            SendCashoutConfirmationEmail::dispatch($cashOut);
            SendCashoutConfirmationSMS::dispatch($cashOut);
            return $cashOut;
        });
    }
    public function failCashOut(VendorCashOut $cashOut): VendorCashOut {
        return DB::transaction(function () use ($cashOut) {

            $cashOut = VendorCashOut::lockForUpdate()
                ->findOrFail($cashOut->id);

            if ($cashOut->status !== 'pending') {
                return $cashOut;
            }

            $vendor = Vendor::lockForUpdate()
                ->findOrFail($cashOut->vendor_id);

            $vendor->increment(
                'wallet_balance',
                $cashOut->amount
            );

            $cashOut->update([
                'status' => 'failed',
            ]);

            SendCashoutFailedEmail::dispatch($cashOut);
            SendCashoutFailedSMS::dispatch($cashOut);
            return $cashOut;
        });
    }
}
