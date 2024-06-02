<?php

declare(strict_types=1);

namespace Invitation\Domain\Exception;

use Exception;

final class InvalidLoyaltyProgramException extends Exception
{
    public function __construct()
    {
        parent::__construct("Invalid Loyalty Program");
    }
}