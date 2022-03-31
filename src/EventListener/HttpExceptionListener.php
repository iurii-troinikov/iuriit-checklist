<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Enum\FlashMessagesEnum;
use App\Exception\ValidationException;
use App\Service\DataTransformService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class HttpExceptionListener
{
    private DataTransformService $dataTransformService;
    public function __construct(DataTransformService $dataTransformService) {
        $this->dataTransformService = $dataTransformService;
    }
    public function onKernelException(ExceptionEvent $event)
    {
        $request = $event->getRequest();
        if (strpos($request->getRequestUri(), '/api/') === 0) {
            $this->handleApiException($event);
            return;
        }
        $exception = $event->getThrowable();
        if (!$exception instanceof ValidationException || !$exception instanceof NotFoundHttpException) {
            return;
        }
        $session = $request->getSession();
        if (!$refererUrl = $request->headers->get('referer')) {
            $refererUrl = '/';
        }
        $response = new RedirectResponse($refererUrl);
        $response->setStatusCode($exception->getStatusCode());
        $response->headers->replace($exception->getHeaders());
        $session->getFlashBag()->add(FlashMessagesEnum::FAIL, $exception->getMessage());
        $event->setResponse($response);
    }
    private function handleApiException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $event->setResponse(new JsonResponse(['errors' => $this->getErrorMessages($exception)], $exception->getCode()));
    }
    private function getErrorMessages(Throwable $exception): array
    {
            if ($exception instanceof ValidationException) {
                return $exception->getErrorsList()
                    ? $this->dataTransformService->transformViolationListToArray($exception->getErrorsList())
                    : [$exception->getMessage()];
            }
            if ($exception instanceof NotFoundHttpException) {
                return [$exception->getMessage()];
            }
            if ($exception instanceof AccessDeniedHttpException) {
                return ['Access denied'];
            }
            return ['Something went wrong ... Try later'];
        }
    }
