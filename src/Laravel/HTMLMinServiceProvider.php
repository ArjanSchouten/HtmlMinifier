<?php

namespace ArjanSchouten\HTMLMin\Laravel;

use ArjanSchouten\HTMLMin\Minify;
use ArjanSchouten\HTMLMin\MinifyPipelineContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;
use Illuminate\Support\ServiceProvider;
use ArjanSchouten\HTMLMin\Laravel\Command\ViewCompilerCommand;
use Illuminate\View\Compilers\BladeCompiler;

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

    protected function registerPHPMinifier()
    {
        $this->app->singleton('php.min', function () {
            return new Minify();
        });
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
            $this->setBladeTags($compiler);

            $context = new MinifyPipelineContext(new PlaceholderContainer());
            return $this->app->make('blade.compiler.min')->process($context->setContents($value))->getContents();
        });
    }

    private function setBladeTags(BladeCompiler $bladeCompiler)
    {
        $contentTags = $bladeCompiler->getContentTags();

        $tags = [
            $contentTags,
            $bladeCompiler->getRawTags(),
            $bladeCompiler->getEscapedContentTags(),
            [$contentTags[0].'--', '--'.$contentTags[1]],
        ];

        return $tags;
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
