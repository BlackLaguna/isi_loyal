<?php

declare(strict_types=1);

namespace Statistic\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Auth\Domain\Client;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class ClientValueResolver implements ValueResolverInterface
{
    public function __construct(private Security $security, private EntityManagerInterface $entityManager)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== Client::class) {
            return [];
        }

        $partner = $this->entityManager
            ->getRepository(Client::class)
            ->find($this->security->getUser()->getUserIdentifier());

        return [$partner];
    }
}