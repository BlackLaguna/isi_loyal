<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Command;

use Auth\Domain\Invitation;

final class AcceptInvitationCommand
{
    public function __construct(
        public readonly Invitation $invitation,
    ) {
    }
}