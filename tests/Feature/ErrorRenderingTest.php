<?php

declare(strict_types=1);

use App\Exceptions\TenantSuspended;
use App\Models\Tenant;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

function throwOn(string $path, Throwable $e): void
{
    Route::get('api/test-errors/'.$path, fn () => throw $e);
}

it('renders each exception with the right status and code', function (Throwable $e, int $status, string $code): void {
    throwOn('case', $e);

    $this->getJson('api/test-errors/case')
        ->assertStatus($status)
        ->assertExactJsonStructure(['error' => ['code', 'message', 'details']])
        ->assertJsonPath('error.code', $code);
})->with([
    'validation' => [fn () => ValidationException::withMessages(['email' => 'Bad email.']), 422, 'VALIDATION_FAILED'],
    'authentication' => [fn () => new AuthenticationException, 401, 'UNAUTHENTICATED'],
    'authorization' => [fn () => new AuthorizationException, 403, 'FORBIDDEN'],
    'access denied' => [fn () => new AccessDeniedHttpException, 403, 'FORBIDDEN'],
    'model not found' => [fn () => (new ModelNotFoundException)->setModel(Tenant::class), 404, 'NOT_FOUND'],
    'not found' => [fn () => new NotFoundHttpException, 404, 'NOT_FOUND'],
    'throttled' => [fn () => new ThrottleRequestsException(headers: ['Retry-After' => 30]), 429, 'RATE_LIMITED'],
    'api exception' => [fn () => new TenantSuspended, 403, 'TENANT_SUSPENDED'],
    'anything else' => [fn () => new RuntimeException('boom'), 500, 'SERVER_ERROR'],
]);

it('puts validation errors in details', function (): void {
    throwOn('validation', ValidationException::withMessages(['email' => 'Bad email.']));

    $this->getJson('api/test-errors/validation')
        ->assertJsonPath('error.details.email.0', 'Bad email.');
});

it('never names the model on a 404', function (): void {
    throwOn('missing', (new ModelNotFoundException)->setModel(Tenant::class, [1]));

    $response = $this->getJson('api/test-errors/missing');

    expect($response->getContent())->not->toContain('Tenant');
});

it('keeps the Retry-After header on 429', function (): void {
    throwOn('throttled', new ThrottleRequestsException(headers: ['Retry-After' => 30]));

    $this->getJson('api/test-errors/throttled')->assertHeader('Retry-After', '30');
});

it('hides the exception message on a 500 when debug is off', function (): void {
    config(['app.debug' => false]);
    throwOn('crash', new RuntimeException('secret detail'));

    $this->getJson('api/test-errors/crash')
        ->assertStatus(500)
        ->assertJsonPath('error.message', 'Server error.')
        ->assertDontSee('secret detail')
        ->assertJsonMissingPath('trace');
});

it('returns NOT_FOUND for an unknown api route', function (): void {
    $this->getJson('api/v1/does-not-exist')
        ->assertNotFound()
        ->assertJsonPath('error.code', 'NOT_FOUND');
});

it('returns 401 instead of redirecting when a request has no token or JSON accept header', function (): void {
    Route::middleware('auth:sanctum')->get('api/test-errors/protected', fn (): array => []);

    $this->get('api/test-errors/protected')
        ->assertUnauthorized()
        ->assertJsonPath('error.code', 'UNAUTHENTICATED');
});
