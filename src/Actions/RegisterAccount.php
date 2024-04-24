<?php

namespace Gildsmith\ProfileApi\Actions;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsController;

class RegisterAccount extends Action
{
    use AsCommand, AsController;

    public string $commandSignature = 'moshi:register {email} {password}';

    public string $commandDescription = 'Create new user account';

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function asController(Request $request): User
    {
        return $this->handle(
            email: $request->input('email'),
            password: $request->input('password')
        );
    }

    public function handle(string $email, string $password): User
    {
        return User::create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }
}
