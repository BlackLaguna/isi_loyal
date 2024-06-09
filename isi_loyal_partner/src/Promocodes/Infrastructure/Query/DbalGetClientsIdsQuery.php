<?php

declare(strict_types=1);

namespace Promocodes\Infrastructure\Query;

use Doctrine\DBAL\Connection;
use Symfony\Component\Uid\AbstractUid;

final class DbalGetClientsIdsQuery
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(AbstractUid $loyaltyProgramUuid, AbstractUid $loyaltyLevelUuid): array
    {
        return $this->connection->executeQuery(
            <<<SQL
                SELECT client_id FROM loyalty_program_client
                WHERE loyalty_program_client.loyalty_program_id = :loyalty_program_uuid
                AND loyalty_program_client.loyalty_level_id = :loyalty_level_uuid
            SQL,
            ['loyalty_program_uuid' => (string) $loyaltyProgramUuid, 'loyalty_level_uuid' => (string) $loyaltyLevelUuid]
        )->fetchAllAssociative();
    }
}