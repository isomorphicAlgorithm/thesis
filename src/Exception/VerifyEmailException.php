<?php

namespace App\Exception;

use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use RuntimeException;

class VerifyEmailException extends \RuntimeException implements VerifyEmailExceptionInterface
{
    private string $reason;

    public function __construct(string $reason)
    {
        parent::__construct($reason);
        $this->reason = $reason;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
