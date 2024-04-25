<?php

use Gildsmith\ProfileApi\Actions\AuthenticateUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', function (Request $request) {
    return $request->user() ?? [];
});

Route::post('login', AuthenticateUser::class);
