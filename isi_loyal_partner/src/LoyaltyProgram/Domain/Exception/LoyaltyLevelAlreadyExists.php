<?php

declare(strict_types=1);

namespace LoyaltyProgram\Domain\Exception;

use Exception;

final class LoyaltyLevelAlreadyExists extends Exception
{
    public function __construct()
    {
        parent::__construct('Loyalty level already exists.');
    }
}