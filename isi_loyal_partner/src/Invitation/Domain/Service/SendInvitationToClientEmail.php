<?php

declare(strict_types=1);

namespace Invitation\Domain\Service;

use Invitation\Domain\ClientEmail;
use Symfony\Component\Uid\AbstractUid;

interface SendInvitationToClientEmail
{
    public function __invoke(ClientEmail $clientEmail, AbstractUid $invitationUuid, string $partnerEmail): void;
}