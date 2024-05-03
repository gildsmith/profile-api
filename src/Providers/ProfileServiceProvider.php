<?php

namespace Gildsmith\ProfileApi\Providers;

use Gildsmith\HubApi\Facades\Gildsmith;
use Gildsmith\HubApi\Router\Web\WebApplication;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootResources();
        $this->bootWebApplication();
        $this->bootApiFeatures();
    }

    /**
     * Load and merge Gildsmith package resources
     * and setups publishable resources.
     */
    public function bootResources(): void
    {
        $packageBasePath = dirname(__DIR__, 2);

        $this->loadViewsFrom($packageBasePath . '/resources/views', 'gildsmith');
        $this->publishes([$packageBasePath . '/resources/views' => resource_path('views/vendor/gildsmith')], 'views');
    }

    protected function bootWebApplication(): void
    {
        $profileApplication = new WebApplication('profile', 'profile', 'gildsmith::template', [
            'app_path' => 'node_modules/@gildsmith/profile-client/src/app.js',
        ]);

        Gildsmith::registerWebApplication($profileApplication);

        // todo
        Route::get('/profile/recovery/{token}', function () use ($profileApplication) {
            return view('gildsmith::template', ['webapp' => $profileApplication]);
        })->name('password.reset');
    }

    protected function bootApiFeatures(): void
    {
        Gildsmith::registerFeatures('authentication', 'registration');

        Gildsmith::registerFeatureRoutes('authentication', function () {
            require dirname(__DIR__, 2) . '/routes/authentication.php';
        });

        Gildsmith::registerFeatureRoutes('registration', function () {
            require dirname(__DIR__, 2) . '/routes/registration.php';
        });
    }
}
