<?php

namespace Gildsmith\ProfileApi\Providers;

use Gildsmith\HubApi\Facades\Gildsmith;
use Gildsmith\HubApi\Router\Web\WebApplication;
use Gildsmith\ProfileApi\Listeners\SendPasswordChangeNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootResources();
        $this->bootWebApplication();
        $this->bootApiFeatures();
        $this->bootListeners();
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

    /**
     * Registers a web application for profile management at
     * the /profile endpoint. It requires the client package
     * to be installed and compiled.
     */
    protected function bootWebApplication(): void
    {
        $profileApplication = new WebApplication(
            identifier: 'profile',
            route: 'profile',
            template: 'gildsmith.template',
            params: [
                'app_path' => 'node_modules/@gildsmith/profile-client/src/app.js',
            ]);

        Gildsmith::registerWebApplication($profileApplication);

        $this->registerPasswordRoutes($profileApplication);
    }

    /**
     * Registers named routes essential for password recovery,
     * aligning with Laravel's default authentication mechanisms.
     * These routes provide links for password reset via emails.
     */
    protected function registerPasswordRoutes(WebApplication $app): void
    {
        Route::get('/profile/recovery', function () use ($app) {
            return view('gildsmith.template', ['webapp' => $app]);
        })->name('password.request');

        Route::get('/profile/recovery/{token}', function () use ($app) {
            return view('gildsmith.template', ['webapp' => $app]);
        })->name('password.reset');
    }

    /**
     * Enables specific API features and registers
     * their corresponding endpoints.
     */
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

    /** TODO */
    protected function bootListeners(): void
    {
        Event::listen(PasswordReset::class, SendPasswordChangeNotification::class);
    }
}
