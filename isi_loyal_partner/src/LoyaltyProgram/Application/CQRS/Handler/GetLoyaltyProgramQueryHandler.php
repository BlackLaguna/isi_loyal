<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Handler;


use LoyaltyProgram\Application\CQRS\Query\GetLoyaltyProgramQuery;
use LoyaltyProgram\Domain\LoyaltyProgram;
use LoyaltyProgram\Domain\LoyaltyProgramRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetLoyaltyProgramQueryHandler
{
    public function __construct(private LoyaltyProgramRepository $repository)
    {
    }

    public function __invoke(GetLoyaltyProgramQuery $query): LoyaltyProgram
    {
        return $this->repository->findByPartnerAndName($query->partner, $query->loyaltyProgramName);
    }
}