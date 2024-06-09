<?php

declare(strict_types=1);

namespace LoyaltyProgram\Application\CQRS\Handler;


use LoyaltyProgram\Application\CQRS\Query\GetLoyaltyProgramQuery;
use LoyaltyProgram\Application\CQRS\Query\GetLoyaltyProgramsQuery;
use LoyaltyProgram\Application\CQRS\Query\View\GetLoyaltyProgramsView;
use LoyaltyProgram\Domain\LoyaltyProgram;
use LoyaltyProgram\Domain\LoyaltyProgramRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetLoyaltyProgramsQueryHandler
{
    public function __construct(private LoyaltyProgramRepository $repository)
    {
    }

    public function __invoke(GetLoyaltyProgramsQuery $query): GetLoyaltyProgramsView
    {
        return new GetLoyaltyProgramsView($this->repository->findAllForPartner($query->partner));
    }
}