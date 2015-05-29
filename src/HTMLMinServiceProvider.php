<?php namespace Arjan96s\HTMLMin;

use Arjan96s\HTMLMin\Compilers\BladeCompiler;
use Illuminate\Support\ServiceProvider;

class HTMLMinServiceProvider extends ServiceProvider {

    protected $defer = true;

    public function boot()
    {
        $this->app->make('blade.compiler')->extend(function($value, $compiler){
            return $this->app->make('blade.compiler.min')->compile;
        });
    }

    public function register()
    {
        $this->registerBladeCompiler();
    }

    protected function registerBladeCompiler()
    {
        $this->app->bind('blade.compiler.min', function(){
            return new BladeCompiler();
        });
    }

    public function provides()
    {
        return [
            'command.optimize',
            'blade.compiler.min',
        ];
    }
}