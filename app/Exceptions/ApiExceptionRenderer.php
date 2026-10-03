<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

final class ApiExceptionRenderer
{
    private const array HTTP_ERRORS = [
        403 => ['FORBIDDEN', 'This action is unauthorized.'],
        404 => ['NOT_FOUND', 'The requested resource was not found.'],
        429 => ['RATE_LIMITED', 'Too many requests. Please slow down.'],
    ];

    public function __construct(private readonly bool $debug) {}

    public function render(Throwable $e): JsonResponse
    {
        return match (true) {
            $e instanceof ApiException => $this->json($e->status(), $e->errorCode(), $e->getMessage(), $e->details()),
            $e instanceof ValidationException => $this->json(422, 'VALIDATION_FAILED', 'The given data was invalid.', $e->errors()),
            $e instanceof AuthenticationException => $this->json(401, 'UNAUTHENTICATED', 'Unauthenticated.'),
            $e instanceof HttpExceptionInterface => $this->renderHttp($e),
            default => $this->json(500, 'SERVER_ERROR', $this->debug ? $e->getMessage() : 'Server error.'),
        };
    }

    private function renderHttp(HttpExceptionInterface $e): JsonResponse
    {
        $status = $e->getStatusCode();
        [$code, $message] = self::HTTP_ERRORS[$status]
            ?? ['HTTP_ERROR', JsonResponse::$statusTexts[$status] ?? 'HTTP error.'];

        return $this->json($status, $code, $message)->withHeaders($e->getHeaders());
    }

    /** @param array<string, mixed> $details */
    private function json(int $status, string $code, string $message, array $details = []): JsonResponse
    {
        return new JsonResponse(
            ['error' => ['code' => $code, 'message' => $message, 'details' => (object) $details]],
            $status,
        );
    }
}
