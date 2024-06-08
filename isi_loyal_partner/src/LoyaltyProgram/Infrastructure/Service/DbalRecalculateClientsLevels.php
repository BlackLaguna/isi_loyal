<?php

declare(strict_types=1);

namespace LoyaltyProgram\Infrastructure\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use LoyaltyProgram\Domain\Service\RecalculateClientsLoyaltyLevels;
use Symfony\Component\Uid\AbstractUid;

class DbalRecalculateClientsLevels implements RecalculateClientsLoyaltyLevels
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(AbstractUid $loyaltyProgramUuid)
    {

    }
}