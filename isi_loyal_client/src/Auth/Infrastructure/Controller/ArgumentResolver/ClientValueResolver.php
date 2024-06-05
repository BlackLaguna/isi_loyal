<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Controller\ArgumentResolver;

use Auth\Domain\Client;
use Auth\Domain\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class ClientValueResolver implements ValueResolverInterface
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== Client::class) {
            return [];
        }

        $client = $this->clientRepository->getUserByEmail($request->attributes->get('client_email'));

        return [$client];
    }
}