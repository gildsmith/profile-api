<?php

use Gildsmith\ProfileApi\Actions\AuthenticateUser;
use Gildsmith\ProfileApi\Actions\ChangePassword;
use Gildsmith\ProfileApi\Actions\LogoutUser;
use Gildsmith\ProfileApi\Actions\RecoveryCompletion;
use Gildsmith\ProfileApi\Actions\RecoveryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * These routes are activated as part of the 'authentication' feature,
 * which is enabled by default. If you do not need this feature, it can
 * be disabled in the Gildsmith configuration settings.
 */

Route::get('user', function (Request $request) {
    return $request->user() ?? [];
});

Route::post('login', AuthenticateUser::class);
Route::post('logout', LogoutUser::class);

Route::post('recovery', RecoveryRequest::class);
Route::post('recovery/{token}', RecoveryCompletion::class);

Route::post('password', ChangePassword::class);
