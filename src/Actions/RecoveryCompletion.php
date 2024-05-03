<?php

namespace Gildsmith\ProfileApi\Actions;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

/**
 * Facilitates the completion of the password reset process
 * for users who have requested recovery. It validates the reset
 * token, updates the password, and triggers relevant system events.
 */
class RecoveryCompletion extends Action
{
    use AsController;

    public function rules(): array
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function asController(Request $request, string $token): JsonResponse
    {
        $status = $this->handle(
            $request->post('email'),
            $request->post('password'),
            $token,
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json()
            : response()->json($this->error($status), 401);
    }

    public function handle(string $email, string $password, string $token): string
    {
        $successCallback = function (User $user, string $password) {
            $user->password = $password;
            $user->setRememberToken(Str::random(60));
            $user->save();

            event(new PasswordReset($user));
        };

        return Password::reset(compact('email', 'password', 'token'), $successCallback);
    }

    /**
     * Constructs an error response consistent with Laravel's default
     * validation error format, specifically for authentication failures.
     */
    public function error(string $status): array
    {
        return [
            'errors' => [
                'common' => [__($status)],
            ],
        ];
    }
}
