<?php

declare(strict_types=1);

namespace LoyaltyProgram\Infrastructure\Controller\ArgumentResolver;

use Doctrine\ORM\EntityManagerInterface;
use LoyaltyProgram\Domain\LoyaltyProgram;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class LoyaltyProgramValueResolver implements ValueResolverInterface
{
    public function __construct(private EntityManagerInterface $invitationEntityManager)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== LoyaltyProgram::class) {
            return [];
        }

        $loyaltyProgram = $this->invitationEntityManager
            ->getRepository(LoyaltyProgram::class)
            ->find($request->attributes->get('loyalty_program_uuid'));

        if (!$loyaltyProgram) {
            throw new NotFoundHttpException();
        }

        return [$loyaltyProgram];
    }
}