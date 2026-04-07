<?php

namespace App\Providers;

use Roots\Acorn\Sage\SageServiceProvider;
use App\Support\Shortcodes;
use App\Support\Elementor\Skins\Buttons\Buttons;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton('Shortcodes', function () {
            return new Shortcodes();
        });

        $this->app->singleton('Buttons', function () {
            return new Buttons();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $toInitialize = ['Shortcodes', 'Buttons'];
        foreach ($toInitialize as $service) {
            $this->app->make($service);
        }
    }
}
