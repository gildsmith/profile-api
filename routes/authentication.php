<?php

use Gildsmith\ProfileApi\Actions\AuthenticateUser;
use Gildsmith\ProfileApi\Actions\RecoveryCompletion;
use Gildsmith\ProfileApi\Actions\RecoveryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', function (Request $request) {
    return $request->user() ?? [];
});

Route::post('login', AuthenticateUser::class);

Route::post('recovery', RecoveryRequest::class);
Route::post('recovery/{token}', RecoveryCompletion::class);

