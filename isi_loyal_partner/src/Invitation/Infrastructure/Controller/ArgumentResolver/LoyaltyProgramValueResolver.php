<?php

declare(strict_types=1);

namespace Invitation\Infrastructure\Controller\ArgumentResolver;

use Doctrine\ORM\EntityManagerInterface;
use Invitation\Domain\LoyaltyProgram;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class LoyaltyProgramValueResolver implements ValueResolverInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== LoyaltyProgram::class) {
            return [];
        }

        $loyaltyProgram = $this->entityManager
            ->getRepository(LoyaltyProgram::class)
            ->find($request->attributes->get('loyalty_program_uuid'));

        if (!$loyaltyProgram) {
            throw new NotFoundHttpException();
        }

        return [$loyaltyProgram];
    }
}