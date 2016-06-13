<?php

namespace Seek\NLPTool;

use Illuminate\Support\ServiceProvider;

class NLPToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/nlp.php' => config_path('nlp.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['seek.NLPTool'] = $this->app->share(function ($app) {
            return new NLPUtils($app['config']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['seek.NLPTool',\Seek\NLPTool\NLPToolServiceProvider::class];
    }
}
