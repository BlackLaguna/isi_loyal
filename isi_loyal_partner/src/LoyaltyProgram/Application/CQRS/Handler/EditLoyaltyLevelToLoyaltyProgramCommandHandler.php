<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Handler;

use LoyaltyProgram\Application\CQRS\Command\EditLoyaltyLevelToLoyaltyProgramCommand;
use LoyaltyProgram\Domain\Exception\LoyaltyLevelNotExists;
use LoyaltyProgram\Domain\LoyaltyLevel\ValueFactor;
use LoyaltyProgram\Domain\LoyaltyProgramRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class EditLoyaltyLevelToLoyaltyProgramCommandHandler
{
    public function __construct(
        public LoyaltyProgramRepository $loyaltyProgramRepository,
    ) {
    }

    /**
     * @throws LoyaltyLevelNotExists
     */
    public function __invoke(EditLoyaltyLevelToLoyaltyProgramCommand $command): void
    {
        $command->loyaltyProgram->editLoyaltyLevel(
            $command->loyaltyLevelId,
            ValueFactor::createFromInt($command->loyaltyLevelValueFactor),
            $command->loyaltyLevelName,
        );
        //dd($command->loyaltyProgram);
        $this->loyaltyProgramRepository->persist($command->loyaltyProgram);
    }
}