<?php

use Gildsmith\ProfileApi\Actions\RegisterAccount;
use Illuminate\Support\Facades\Route;

/*
 * These routes are activated as part of the 'registration' feature,
 * which is enabled by default. If you do not need this feature, it can
 * be disabled in the Gildsmith configuration settings.
 */

Route::post('register', RegisterAccount::class);
