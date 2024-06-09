<?php

declare(strict_types=1);

namespace ClientPurchases\Infrastructure\Controller\ArgumentResolver;

use ClientPurchases\Domain\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ClientValueResolver implements ValueResolverInterface
{
    public function __construct(private EntityManagerInterface $entityManger)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== Client::class) {
            return [];
        }

        $client = $this->entityManger
            ->getRepository(Client::class)
            ->find($request->attributes->get('client_email'));

        if (null === $client) {
            throw new NotFoundHttpException();
        }

        return [$client];
    }
}