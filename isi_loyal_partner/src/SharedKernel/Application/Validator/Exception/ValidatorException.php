<?php

declare(strict_types=1);

namespace SharedKernel\Application\Validator\Exception;

use Exception;

class ValidatorException extends Exception
{
    public function __construct(public readonly array $errors)
    {
        parent::__construct();
    }
}