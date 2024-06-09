<?php

declare(strict_types=1);

namespace ClientPurchases\Application\CQRS\Handler;

use ClientPurchases\Application\CQRS\Query\GetClientLoyaltyProgramStatisticsQuery;
use ClientPurchases\Domain\LoyaltyProgramClientRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetClientLoyaltyProgramStatisticsQueryHandler
{
    public function __construct(private LoyaltyProgramClientRepository $clientPurchasesRepository)
    {
    }

    public function __invoke(GetClientLoyaltyProgramStatisticsQuery $command): array
    {
        $clientLoyaltyProgram = $this->clientPurchasesRepository->findByClientAndLoyaltyProgram(
            $command->client,
            $command->loyaltyProgram
        );

        return $clientLoyaltyProgram->jsonSerialize();
    }
}