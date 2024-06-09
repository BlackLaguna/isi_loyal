<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Handler;

use LoyaltyProgram\Application\CQRS\Command\AddLoyaltyLevelToLoyaltyProgramCommand;
use LoyaltyProgram\Domain\Exception\LoyaltyLevelAlreadyExists;
use LoyaltyProgram\Domain\LoyaltyLevel\ValueFactor;
use LoyaltyProgram\Domain\LoyaltyProgramRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class AddLoyaltyLevelToLoyaltyProgramCommandHandler
{
    public function __construct(
        public LoyaltyProgramRepository $loyaltyProgramRepository,
    ) {
    }

    /** @throws LoyaltyLevelAlreadyExists */
    public function __invoke(AddLoyaltyLevelToLoyaltyProgramCommand $command): void
    {
        $command->loyaltyProgram->addNewLoyaltyLevel(
            ValueFactor::createFromInt($command->loyaltyLevelValueFactor),
            $command->loyaltyLevelName,
        );
        $this->loyaltyProgramRepository->persist($command->loyaltyProgram);
    }
}