<?php

namespace ArjanSchouten\HTMLMin;

use ArjanSchouten\HTMLMin\Command\ViewCompilerCommand;
use ArjanSchouten\HTMLMin\Minifiers\Blade\Blade;
use ArjanSchouten\HTMLMin\Minifiers\Html\AttributeQuoteMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\CommentMinifier;
use ArjanSchouten\HTMLMin\Minifiers\Html\EmptyAttribute;
use ArjanSchouten\HTMLMin\Minifiers\Html\JavascriptEvents;
use ArjanSchouten\HTMLMin\Minifiers\Html\Link;
use ArjanSchouten\HTMLMin\Minifiers\Html\Script;
use ArjanSchouten\HTMLMin\Minifiers\Html\Style;
use ArjanSchouten\HTMLMin\Minifiers\Html\Whitespace;
use Event;
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
        $this->registerBladeCompiler();

        $this->registerHtmlMinifiers();
        $this->registerBladeMinifiers();

        $this->addCommands();
    }

    /**
     * Register the blade minifier.
     *
     * @return void
     */
    protected function registerBladeCompiler()
    {
        $this->app->bind('blade.compiler.min', function () {
            $minifiers = Event::fire('bladeMinify');

            return new Minify($minifiers[0]);
        });
    }

    /**
     * Register html minifiers.
     *
     * @return void
     */
    protected function registerHtmlMinifiers()
    {
        $this->app->bind('link', Link::class);
        $this->app->bind('style', Style::class);
        $this->app->bind('script', Script::class);
        $this->app->bind('comment', CommentMinifier::class);
        $this->app->bind('whitespace', Whitespace::class);
        $this->app->bind('attributequote', AttributeQuoteMinifier::class);
        $this->app->bind('emptyattributes', EmptyAttribute::class);
        $this->app->bind('javascriptevents', JavascriptEvents::class);
        $this->app->tag([
            'comment',
            'whitespace',
            'script',
            'link',
            'style',
            'attributequote',
            'javascriptevents',
            'emptyattributes'
        ], 'htmlMinifyRules');
    }

    /**
     * Register blade minifiers.
     *
     * @return void
     */
    protected function registerBladeMinifiers()
    {
        $this->app->bind('blade', Blade::class);
        $this->app->tag([
            'comment',
            'whitespace',
            'blade',
            'script',
            'link',
            'style',
            'attributequote',
            'javascriptevents',
            'emptyattributes'
        ], 'bladeMinifyRules');
    }

    /**
     * Add the available commands.
     *
     * @return void
     */
    protected function addCommands()
    {
        $this->commands(ViewCompilerCommand::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addCompilerExtensions();
    }

    /**
     * Extend the original blade compiler with minification rules.
     *
     * @return void
     */
    protected function addCompilerExtensions()
    {
        $this->app->make('blade.compiler')->extend(function ($value, $compiler) {
            return $this->app->make('blade.compiler.min')->executeMinification($value);
        });
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