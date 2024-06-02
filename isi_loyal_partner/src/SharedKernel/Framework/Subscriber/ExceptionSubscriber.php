<?php

declare(strict_types=1);

namespace SharedKernel\Framework\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionSubscriber implements EventSubscriberInterface
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
        if ('dev' === $_SERVER['APP_ENV']) {
            return;
        }

        $throwable = $event->getThrowable();

        $event->setResponse(
            new JsonResponse(
                ['reason' => $throwable->getMessage()],
                Response::HTTP_BAD_REQUEST,
            )
        );
    }
}