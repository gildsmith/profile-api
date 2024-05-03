<?php

namespace Gildsmith\ProfileApi\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

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

    public function asController(Request $request)
    {
        return $this->handle(
            request: $request,
            email: $request->input('email'),
            password: $request->input('password'),
            remember: $request->input('remember') ?? false,
        );
    }

    public function handle(Request $request, string $email, string $password, bool $remember)
    {
        if (Auth::attempt(compact('email', 'password'), $remember)) {
            return $request->user();
        }

        return response()->json($this->error(), 403);
    }

    public function error(): array
    {
        return [
            'errors' => [
                'password' => ['The provided credentials do not match our records.']
            ]
        ];
    }
}
