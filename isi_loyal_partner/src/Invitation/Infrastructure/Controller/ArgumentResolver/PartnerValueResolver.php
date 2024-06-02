<?php

declare(strict_types=1);

namespace Invitation\Infrastructure\Controller\ArgumentResolver;

use Doctrine\ORM\EntityManagerInterface;
use Invitation\Domain\Partner;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class PartnerValueResolver implements ValueResolverInterface
{
    public function __construct(private Security $security, private EntityManagerInterface $invitationEntityManager)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== Partner::class) {
            return [];
        }

        $partner = $this->invitationEntityManager
            ->getRepository(Partner::class)
            ->find($this->security->getUser()->getUserIdentifier());

        return [$partner];
    }
}