<?php

namespace OnrampLab\Webhooks;

use Illuminate\Support\ServiceProvider;
use OnrampLab\Webhooks\Contracts\Webhookable;
use Illuminate\Events\Dispatcher;

class WebhooksServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
         $this->mergeConfigFrom(__DIR__ . '/../config/laravel-webhooks.php', 'laravel-webhooks');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'webhooks-migrations');

        $this->publishes([
            __DIR__ . '/../config/laravel-webhooks.php' => config_path('laravel-webhooks.php'),
        ], 'laravel-webhooks-config');

        // Register the WebhookDispatcher for events that implement Webhookable interface
        $this->app[Dispatcher::class]->listen(Webhookable::class, WebhookDispatcher::class);

    }
}
