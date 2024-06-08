<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Handler;

use LoyaltyProgram\Application\CQRS\Command\AddLoyaltyLevelToLoyaltyProgramCommand;
use LoyaltyProgram\Domain\Exception\LoyaltyLevelAlreadyExists;
use LoyaltyProgram\Domain\LoyaltyLevel\ValueFactor;
use LoyaltyProgram\Domain\Service\RecalculateClientsLoyaltyLevels;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class AddLoyaltyLevelToLoyaltyProgramCommandHandler
{
    public function __construct(
        private RecalculateClientsLoyaltyLevels $recalculateClientsLoyaltyLevels,
    ) {
    }

    /** @throws LoyaltyLevelAlreadyExists */
    public function __invoke(AddLoyaltyLevelToLoyaltyProgramCommand $command): void
    {
        $command->loyaltyProgram->addLoyaltyLevel(
            new ValueFactor($command->loyaltyLevelValueFactor),
            $this->recalculateClientsLoyaltyLevels,
        );
    }
}