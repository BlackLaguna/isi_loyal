<?php

declare(strict_types=1);

namespace LoyaltyProgram\Infrastructure\Controller\ArgumentResolver;

use Doctrine\ORM\EntityManagerInterface;
use LoyaltyProgram\Domain\Partner;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class PartnerValueResolver implements ValueResolverInterface
{
    public function __construct(private Security $security, private EntityManagerInterface $entityManger)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== Partner::class) {
            return [];
        }

        $partner = $this->entityManger
            ->getRepository(Partner::class)
            ->find($this->security->getUser()->getUserIdentifier());

        return [$partner];
    }
}