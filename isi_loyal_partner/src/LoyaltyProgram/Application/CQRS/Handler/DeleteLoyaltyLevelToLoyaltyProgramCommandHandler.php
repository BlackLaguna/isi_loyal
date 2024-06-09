<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Handler;

use LoyaltyProgram\Application\CQRS\Command\DeleteLoyaltyLevelToLoyaltyProgramCommand;
use LoyaltyProgram\Domain\Exception\LoyaltyLevelNotExists;
use LoyaltyProgram\Domain\LoyaltyProgramRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class DeleteLoyaltyLevelToLoyaltyProgramCommandHandler
{
    public function __construct(
        public LoyaltyProgramRepository $loyaltyProgramRepository,
    ) {
    }

    /**
     * @throws LoyaltyLevelNotExists
     */
    public function __invoke(DeleteLoyaltyLevelToLoyaltyProgramCommand $command): void
    {
        $command->loyaltyProgram->deleteLoyaltyLevel($command->loyaltyLevelId);
        $this->loyaltyProgramRepository->persist($command->loyaltyProgram);
    }
}