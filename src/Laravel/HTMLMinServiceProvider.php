<?php

namespace ArjanSchouten\HTMLMin\Laravel;

use ArjanSchouten\HTMLMin\Laravel\Command\ViewCompilerCommand;
use ArjanSchouten\HTMLMin\Minify;
use Illuminate\Support\ServiceProvider;

class HTMLMinServiceProvider extends ServiceProvider
{
    /**
     * Defer loading the service provider until the provided services are needed.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBladeMinifier();

        $this->addCommands();
    }

    /**
     * Register the blade minifier.
     *
     * @return void
     */
    protected function registerBladeMinifier()
    {
        $this->app->singleton('blade.compiler.min', function () {
            return new Minify();
        });
    }

    /**
     * Register the php minifier.
     *
     * @return void
     */
    protected function registerPHPMinifier()
    {
        $this->app->singleton('php.min', function () {
            return new Minify();
        });
    }

    /**
     * Add the available CLI commands.
     *
     * @return void
     */
    protected function addCommands()
    {
        $this->commands(ViewCompilerCommand::class);
    }

    /**
     * Services provided by this service provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'minify:views',
        ];
    }
}
