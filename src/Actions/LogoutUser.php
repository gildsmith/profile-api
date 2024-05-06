<?php

namespace Gildsmith\ProfileApi\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

class LogoutUser extends Action
{
    use AsController;

    public function handle(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function asController(Request $request): JsonResponse
    {
        $this->handle($request);
        return response()->json(null, 204);
    }
}