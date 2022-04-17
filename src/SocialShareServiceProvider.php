<?php

namespace Jringeisen\SocialShare;

use Illuminate\Support\ServiceProvider;

class SocialShareServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load the required routes
        $this->loadRoutesFrom(realpath(__DIR__ . '/routes.php'));

        // Load the database migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
