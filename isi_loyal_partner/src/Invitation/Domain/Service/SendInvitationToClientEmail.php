<?php

declare(strict_types=1);

namespace Invitation\Domain\Service;

use Invitation\Domain\ClientEmail;
use Symfony\Component\Uid\Uuid;

interface SendInvitationToClientEmail
{
    public function __invoke(ClientEmail $clientEmail, Uuid $invitationUuid, string $partnerEmail): void;
}