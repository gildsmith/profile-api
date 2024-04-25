<?php

namespace Gildsmith\ProfileApi\Providers;

use Gildsmith\HubApi\Facades\Gildsmith;
use Gildsmith\HubApi\Router\Web\WebApplication;
use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootWebApplication();
        $this->bootApiFeatures();
    }

    protected function bootWebApplication(): void
    {
        $profileApplication = new WebApplication('profile', 'profile', 'gildsmith::template', [
            'app_path' => 'node_modules/@gildsmith/profile-client/src/app.js',
        ]);

        Gildsmith::registerWebApplication($profileApplication);
    }

    protected function bootApiFeatures(): void
    {
        Gildsmith::registerFeatures('authentication', 'registration');

        Gildsmith::registerFeatureRoutes('authentication', function () {
            require dirname(__DIR__, 2).'/routes/authentication.php';
        });

        Gildsmith::registerFeatureRoutes('registration', function () {
            require dirname(__DIR__, 2).'/routes/registration.php';
        });
    }
}
