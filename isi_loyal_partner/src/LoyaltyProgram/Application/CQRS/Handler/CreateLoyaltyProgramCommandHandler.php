<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Handler;

use LoyaltyProgram\Application\CQRS\Command\CreateLoyaltyProgramCommand;
use LoyaltyProgram\Domain\LoyaltyProgram;
use LoyaltyProgram\Domain\LoyaltyProgramRepository;
use LoyaltyProgram\Domain\Service\IsPartnerAlreadyHasLoyaltyProgramWithSameName;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateLoyaltyProgramCommandHandler
{
    public function __construct(
        private LoyaltyProgramRepository $repository,
        private IsPartnerAlreadyHasLoyaltyProgramWithSameName $alreadyHasLoyaltyProgramWithSameName,
    ) {
    }

    public function __invoke(CreateLoyaltyProgramCommand $command): void
    {
        $this->repository->persist(LoyaltyProgram::createNew(
            $command->partner,
            $command->loyaltyProgramName,
            $this->alreadyHasLoyaltyProgramWithSameName,
        ));
    }
}