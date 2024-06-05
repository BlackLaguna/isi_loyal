<?php

declare(strict_types=1);

namespace Auth\Domain\Exception;

use SharedKernel\Application\Validator\Exception\ValidatorException;

class UserAlreadyExist extends ValidatorException
{
    public function __construct()
    {
        parent::__construct(['errors' => 'Email already registered']);
    }
}