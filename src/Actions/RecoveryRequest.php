<?php

namespace Gildsmith\ProfileApi\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

/**
 * Initiates a password reset process by sending
 * a reset link to the user's email address provided.
 */
class RecoveryRequest extends Action
{
    use AsController;

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    public function asController(Request $request): void
    {
        $this->handle($request->post('email'));
    }

    public function handle(string $email): void
    {
        Password::sendResetLink(compact('email'));
    }
}
