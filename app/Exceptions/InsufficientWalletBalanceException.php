<?php

namespace App\Exceptions;

use Exception;

class InsufficientWalletBalanceException extends Exception
{
    public function __construct()
    {
        parent::__construct('Insufficient wallet balance.');
    }
}
