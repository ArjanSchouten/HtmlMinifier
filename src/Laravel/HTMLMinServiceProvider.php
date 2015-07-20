<?php

namespace ArjanSchouten\HTMLMin\Laravel;

use ArjanSchouten\HTMLMin\Minify;
use ArjanSchouten\HTMLMin\Placeholders\Blade\BladePlaceholder;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use ArjanSchouten\HTMLMin\PlaceholderContainer;
use ArjanSchouten\HTMLMin\MinifyPipelineContext;
use ArjanSchouten\HTMLMin\Laravel\Command\ViewCompilerCommand;

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
            BladePlaceholder::setBladeTags($this->getBladeTags($compiler));

            $context = new MinifyPipelineContext(new PlaceholderContainer());
            return $this->app->make('blade.compiler.min')->process($context->setContents($value))->getContents();
        });
    }

    /**
     * Get the blade tags which might be overruled by user.
     *
     * @param  \Illuminate\View\Compilers\BladeCompiler  $bladeCompiler
     * @return array
     */
    private function getBladeTags(BladeCompiler $bladeCompiler)
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
