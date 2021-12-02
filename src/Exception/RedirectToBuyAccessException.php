<?php

namespace App\Exception;

use App\Entity\Security\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectToBuyAccessException extends \Exception
{
    public function __construct(
        $message = '',
        $code = 0,
        \Exception $previousException = null,
    ) {
        parent::__construct($message, $code, $previousException);
    }
}