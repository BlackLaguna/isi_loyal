<?php

declare(strict_types=1);

namespace Invitation\Domain;

interface InvitationRepository
{
    public function save(Invitation $invitation): void;
}