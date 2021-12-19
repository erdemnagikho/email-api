<?php

namespace App\EventListener\Exception;

use Psr\Log\LoggerInterface;
use App\Exception\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(protected LoggerInterface $logger)
    {}

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = match (true) {
            ($exception instanceof ApiExceptionInterface) => $this->getApiExceptionResponse($exception),
            default => $this->getThrowableExceptionResponse($exception),
        };

        $event->setResponse($response);
    }

    /**
     * @param ApiExceptionInterface $exception
     *
     * @return JsonResponse
     */
    protected function getApiExceptionResponse(ApiExceptionInterface $exception): JsonResponse
    {
        $content = [
            'success' => false,
            'message' => $exception->getMessage(),
            'errors' => $exception->getErrors(),
        ];

        return new JsonResponse($content, $exception->getCode());
    }

    /**
     * @param \Throwable $exception
     *
     * @return JsonResponse
     */
    protected function getThrowableExceptionResponse(\Throwable $exception): JsonResponse
    {
        $content = [
            'success' => false,
            'message' => $exception->getMessage(),
        ];

        $this->logger->error(sprintf('[ExceptionListener][ThrowableExceptionResponse] %s', $exception), []);

        return new JsonResponse($content, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}