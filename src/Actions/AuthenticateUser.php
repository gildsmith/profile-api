<?php

namespace Gildsmith\ProfileApi\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

/**
 * Handles user authentication via API, validating credentials
 * and returning user data upon successful authentication.
 */
class AuthenticateUser extends Action
{
    use AsController;

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    public function asController(Request $request): JsonResponse
    {
        $result = $this->handle(
            $request->input('email'),
            $request->input('password'),
            $request->input('remember', false),
        );

        return $result
            ? response()->json($request->user())
            : response()->json($this->error(), 403);
    }

    public function handle(string $email, string $password, bool $remember): bool
    {
        return Auth::attempt(compact('email', 'password'), $remember);
    }

    /**
     * Constructs an error response consistent with Laravel's default
     * validation error format, specifically for authentication failures.
     */
    public function error(): array
    {
        return [
            'errors' => [
                'password' => ['The provided credentials do not match our records.'],
            ],
        ];
    }
}
