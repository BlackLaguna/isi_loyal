<?php

declare(strict_types=1);

namespace SharedKernel\Framework\Subscriber;

use SharedKernel\Application\Validator\Exception\ValidatorException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ValidatorExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof ValidatorException) {
            $event->setResponse(
                new JsonResponse(
                    ['errors' => $throwable->errors],
                    Response::HTTP_BAD_REQUEST,
                    ['Content-Type' => 'application/json']
                )
            );
        }
    }
}
