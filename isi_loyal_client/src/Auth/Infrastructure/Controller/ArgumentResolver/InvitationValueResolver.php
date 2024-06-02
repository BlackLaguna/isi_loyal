<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Controller\ArgumentResolver;

use Auth\Domain\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class InvitationValueResolver implements ValueResolverInterface
{
    public function __construct(private EntityManagerInterface $invitationEntityManager)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== Invitation::class) {
            return [];
        }

        $invitation = $this->invitationEntityManager
            ->getRepository(Invitation::class)
            ->find($request->attributes->get('invitation_uuid'));

        if (!$invitation) {
            throw new NotFoundHttpException();
        }

        return [$invitation];
    }
}