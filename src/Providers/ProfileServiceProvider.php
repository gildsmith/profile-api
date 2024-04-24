<?php

namespace Gildsmith\ProfileApi\Providers;

use Gildsmith\HubApi\Facades\Gildsmith;
use Gildsmith\HubApi\Router\Web\WebApplication;
use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gildsmith::registerWebApplication(new WebApplication(
            identifier: 'profile',
            route: 'profile',
            template: 'gildsmith::template',
            params: [
                'app_path' => 'node_modules/@gildsmith/profile-client/src/app.js',
            ]
        ));
    }
}
