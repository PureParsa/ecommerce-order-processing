<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(string $productName, int $requested, int $available)
    {
        $message = "Product '{$productName}' has only {$available} in stock, requested {$requested}";
        parent::__construct($message);
    }
}
