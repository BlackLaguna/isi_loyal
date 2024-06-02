<?php

declare(strict_types=1);

namespace Invitation\Domain;

enum InvitationStatus: string
{
    case NEW = 'NEW';
    case SENT = 'SENT';
    case REJECTED = 'REJECTED';
    case ACCEPTED = 'ACCEPTED';
}