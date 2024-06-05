<?php

declare(strict_types=1);

namespace Auth\Domain\Exception;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Client not found');
    }
}