<?php

declare(strict_types=1);

namespace Statistic\Infrastructure;

use Doctrine\DBAL\Connection;

class GetClientStatisticQuery
{
    public function __construct(public Connection $connection)
    {
    }

    public function __invoke(string $clientId): array
    {
       $clientStatistics = $this->connection->executeQuery(
            <<<SQL
                SELECT lpc.loyalty_program_id, lp.name as lp_name, lvl.name as level_name, lpc.value_factor FROM loyalty_program_client lpc
                JOIN loyalty_programs lp ON lp.id = lpc.loyalty_program_id
                LEFT JOIN loyalty_levels lvl ON lvl.id = lpc.loyalty_level_id
                WHERE lpc.client_id = :client_id
            SQL,
            ['client_id' => $clientId]
        )->fetchAllAssociative();

       foreach ($clientStatistics as $key => $clientStatistic) {
           if ($clientStatistic['level_name'] !== null) {
               $clientStatistics[$key]['next_level'] = $this->connection->executeQuery(
                   <<<SQL
                        SELECT * FROM loyalty_levels
                        WHERE loyalty_program_id = :loyalty_program_id
                        AND value_factor > :value_factor
                        ORDER BY value_factor ASC
                        LIMIT 1
                    SQL,
                   [
                       'loyalty_program_id' => $clientStatistic['loyalty_program_id'],
                       'value_factor' => $clientStatistic['value_factor'],
                   ]
               )
               ->fetchAssociative();
           }

           $clientStatistics[$key]['promocodes'] = $this->connection->executeQuery(
               <<<SQL
                        SELECT * FROM promocodes
                        WHERE loyalty_program_id = :loyalty_program_id
                        AND client_id = :client_id
                    SQL,
               [
                   'loyalty_program_id' => $clientStatistic['loyalty_program_id'],
                   'client_id' => $clientId,
               ]
           )
           ->fetchAssociative();
       }

       return $clientStatistics;
    }
}