<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Controller;

use Auth\Application\CQRS\Command\RegisterUserCommand;
use Auth\Application\CQRS\Query\GetUserQuery;
use Auth\Infrastructure\Controller\Request\RegistrationRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use SharedKernel\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class AuthController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload(acceptFormat: 'json')] RegistrationRequest $request,
        MessageBusInterface $commandBus,
        QueryBus $queryBus,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $commandBus->dispatch(new RegisterUserCommand($request->email, $request->password));
        $user = $queryBus->dispatch(new GetUserQuery($request->email));

        return new JsonResponse(['token' => $jwtManager->create($user)], Response::HTTP_CREATED);
    }
}