<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

/**
 * @group Auth
 */
final class PasswordController extends Controller
{
    public function __construct(private readonly AuthService $auth) {}

    /**
     * Send a set-password token
     *
     * Emails a token for POST /auth/reset-password. Always returns 202, so the API never reveals which emails exist.
     *
     * @unauthenticated
     *
     * @response 202 {"data": {"message": "If the email is registered, a reset token has been sent."}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"email": ["The email field must be a valid email address."]}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $this->auth->requestPasswordReset($request->email());

        return new JsonResponse(['data' => ['message' => 'If the email is registered, a reset token has been sent.']], 202);
    }

    /**
     * Set a new password
     *
     * Uses the token from the forgot-password email or from the new-user invitation email.
     *
     * @unauthenticated
     *
     * @response 200 {"data": {"message": "Your password has been set."}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"token": ["This password reset token is invalid."]}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $this->auth->resetPassword($request->toDto());

        return new JsonResponse(['data' => ['message' => 'Your password has been set.']]);
    }
}
