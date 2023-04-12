<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionHandlerTrait
{
    /**
     * Report or log an exception.
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     */
    public function report(\Throwable $e): void
    {
        parent::report($e instanceof ApiException ? $e->toReport() : $e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render($request, \Throwable $e)
    {
        $e = $this->resolveException($e);

        $response = response()->json($this->formatApiResponse($e), $e->getCode(), $e->getHeaders());

        return $response->withException($e);
    }

    /**
     * Define exception.
     */
    protected function resolveException(\Throwable $e): ApiException
    {
        switch (true) {
            case $e instanceof ApiException:
                break;
            case $e instanceof AuthorizationException:
                $e = new ForbiddenApiException('', $e);
                break;
            case $e instanceof AuthenticationException:
                $e = new UnauthorizedApiException('', $e);
                break;
            case $e instanceof ValidationException:
                $e = new ValidationFailedApiException($e->validator->getMessageBag()->toArray(), '');
                break;
            case $e instanceof MethodNotAllowedHttpException:
                $e = new MethodNotAllowedApiException('', $e);
                break;
            case $e instanceof ModelNotFoundException:
            case $e instanceof NotFoundHttpException:
                $e = new NotFoundApiException();
                break;
            default:
                $e = new InternalServerErrorApiException('', $e);
                break;
        }

        return $e;
    }

    /**
     * Format error message for API response.
     */
    protected function formatApiResponse(ApiException $exception): array
    {
        return $exception->toArray();
    }
}
