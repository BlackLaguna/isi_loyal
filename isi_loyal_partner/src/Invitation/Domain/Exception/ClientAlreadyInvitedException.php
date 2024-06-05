<?php

declare(strict_types=1);

namespace Invitation\Domain\Exception;

use Exception;

final class ClientAlreadyInvitedException extends Exception
{
    public function __construct()
    {
        parent::__construct("Client already invited");
    }
}