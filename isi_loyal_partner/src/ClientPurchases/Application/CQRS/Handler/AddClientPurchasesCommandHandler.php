<?php

declare(strict_types=1);

namespace ClientPurchases\Application\CQRS\Handler;

use ClientPurchases\Application\CQRS\Command\AddClientPurchasesCommand;
use ClientPurchases\Domain\LoyaltyProgramClientRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class AddClientPurchasesCommandHandler
{
    public function __construct(private LoyaltyProgramClientRepository $clientPurchasesRepository)
    {
    }

    public function __invoke(AddClientPurchasesCommand $command): void
    {
        $clientLoyaltyProgram = $this->clientPurchasesRepository->findByClientAndLoyaltyProgram(
            $command->client,
            $command->loyaltyProgram
        );
        $clientLoyaltyProgram->generateNewClientPurchases($command->valueFactor);

        $this->clientPurchasesRepository->save($clientLoyaltyProgram);
    }
}