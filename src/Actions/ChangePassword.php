<?php

namespace Gildsmith\ProfileApi\Actions;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

/**
 * Action to change the password of
 * the currently authenticated user.
 */
class ChangePassword extends Action
{
    use AsController;

    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed']
        ];
    }

    public function asController(Request $request): JsonResponse
    {
        $success = $this->handle(
            $request->user(),
            $request->post('new_password'),
        );

        return $success
            ? response()->json()
            : response()->json(null, 401);
    }

    public function handle(User $user, string $password): bool
    {
        $user->password = $password;
        $user->setRememberToken(Str::random(60));
        $result = $user->save();

        if ($result) {
            event(new PasswordReset($user));
        }

        return $result;
    }
}