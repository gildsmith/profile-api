<?php

use Gildsmith\ProfileApi\Actions\RegisterAccount;
use Illuminate\Support\Facades\Route;

Route::post('register', RegisterAccount::class);
