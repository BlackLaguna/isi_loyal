<?php

declare(strict_types=1);

namespace Invitation\Infrastructure\DataBaseType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Invitation\Domain\InvitationStatus;

class DoctrineInvitationStatusType extends Type
{
    private const string NAME = 'enum_invitation_status';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        /** @var string $value */
        return match($value) {
            InvitationStatus::NEW->name => InvitationStatus::NEW,
            InvitationStatus::SENT->name => InvitationStatus::SENT,
            InvitationStatus::ACCEPTED->name => InvitationStatus::ACCEPTED,
            InvitationStatus::REJECTED->name => InvitationStatus::REJECTED,
        };
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}