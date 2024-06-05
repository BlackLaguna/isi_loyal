<?php

declare(strict_types=1);

namespace Auth\Application\CQRS\Handler;

use Auth\Application\CQRS\Query\GetUserQuery;
use Auth\Domain\Client;
use Auth\Domain\ClientRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetUserQueryHandler
{
    public function __construct(private ClientRepository $userRepository)
    {
    }

    public function __invoke(GetUserQuery $getUserQuery): Client
    {
        return $this->userRepository->getUserByEmail($getUserQuery->email);
    }
}