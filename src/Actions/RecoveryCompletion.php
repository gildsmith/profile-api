<?php

namespace Gildsmith\ProfileApi\Actions;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

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
            email: $request->post('email'),
            password: $request->post('password'),
            passwordConfirmation: $request->post('password_confirmation'),
            token: $token
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json()
            : response()->json(['errors' => __($status)], 401);
    }

    public function handle(string $email, string $password, string $passwordConfirmation, string $token): string
    {
        $credentials = [
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
            'token' => $token,
        ];

        $successCallback = function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        };

        return Password::reset($credentials, $successCallback);
    }
}