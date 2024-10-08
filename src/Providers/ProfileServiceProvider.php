<?php

namespace Gildsmith\ProfileApi\Providers;

use Gildsmith\CoreApi\Facades\Gildsmith;
use Gildsmith\CoreApi\Router\Web\WebAppBuilder;
use Gildsmith\ProfileApi\Listeners\SendPasswordChangeNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;

class ProfileServiceProvider extends ServiceProvider
{
    /** NO COMMENT */
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
        $this->loadViewsFrom($this->packagePath('resources/views'), 'gildsmith');
        $this->publishes([$this->packagePath('resources/views') => resource_path('views/vendor/gildsmith')], 'views');
    }

    /**
     * Registers a web application for profile management at
     * the /profile endpoint. It requires the client package
     * to be installed and compiled.
     */
    protected function bootWebApplication(): void
    {
        $app = Gildsmith::app('profile')
            ->route('profile')
            ->template('gildsmith::profile')
            ->param('app_path', 'node_modules/@gildsmith/profile-web/src/app.js');

        $this->registerPasswordRoutes($app);
    }

    /**
     * Registers named routes essential for password recovery,
     * aligning with Laravel's default authentication mechanisms.
     * These routes provide links for password reset via emails.
     */
    protected function registerPasswordRoutes(WebAppBuilder $app): void
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
        Feature::define('authentication', true);
        Feature::define('registration', true);

        Gildsmith::feature('authentication')
            ->file($this->packagePath('routes/authentication.php'))
            ->flagged();

        Gildsmith::feature('registration')
            ->file($this->packagePath('routes/registration.php'))
            ->flagged();
    }

    /** Register listeners */
    protected function bootListeners(): void
    {
        Event::listen(PasswordReset::class, SendPasswordChangeNotification::class);
    }

    /**
     * Helper function to build paths from the package root.
     */
    private function packagePath(string $path): string
    {
        return dirname(__DIR__, 2).'/'.$path;
    }
}
