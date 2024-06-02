<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Handler;

use Auth\Application\CQRS\Query\GetUserQuery;
use Auth\Domain\Partner;
use Auth\Domain\PartnerRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetUserQueryHandler
{
    public function __construct(private PartnerRepository $userRepository)
    {
    }

    public function __invoke(GetUserQuery $getUserQuery): Partner
    {
        return $this->userRepository->getUserByEmail($getUserQuery->email);
    }
}