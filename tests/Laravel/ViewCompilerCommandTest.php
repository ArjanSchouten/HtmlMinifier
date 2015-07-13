<?php

use Mockery as m;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\ViewFinderInterface;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use ArjanSchouten\HTMLMin\Laravel\Command\ViewCompilerCommand;

class ViewCompilerCommandTest extends PHPUnit_Framework_TestCase
{
    private $app;

    public function __construct()
    {
        $this->generateMocks();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testViewCompilerCommand()
    {
        $viewCompilerCommand = new ViewCompilerCommand();
        $viewCompilerCommand->setLaravel($this->app);

        try {
            $viewCompilerCommand->fire();
        } catch (Exception $e) {
            $this->assertEquals('Test Succeeded!', $e->getMessage());
        }
    }

    private function generateMocks()
    {
        $filesystem = m::mock(Filesystem::class)
            ->shouldReceive('allFiles')
            ->with('/home/myuser/path')
            ->andReturn([__DIR__.'/stubs/app.blade.php'])
            ->getMock();

        $viewfinder = m::mock(ViewFinderInterface::class)
            ->shouldReceive('getPaths')
            ->andReturn(['/home/myuser/path'])
            ->getMock();

        $bladeCompiler = m::mock(BladeCompiler::class);

        $compilerEngine = m::mock('alias:'.CompilerEngine::class)
            ->shouldReceive('getCompiler')
            ->andReturn($bladeCompiler)
            ->getMock();

        $viewfactory = m::mock(Factory::class);
        $viewfactory->shouldReceive('getFinder')
            ->andReturn($viewfinder);

        $viewfactory->shouldReceive('getEngineFromPath')
            ->andReturn($compilerEngine);

        $this->app = m::mock(LaravelApplication::class);
        $this->app->shouldReceive('offsetExists')
            ->with('view')
            ->andReturn(true);

        $this->app->shouldReceive('offsetExists')
            ->with('files')
            ->andReturn(true);

        $this->app->shouldReceive('offsetGet')
            ->with('view')
            ->andReturn($viewfactory);

        $this->app->shouldReceive('offsetGet')->with('files')
            ->andReturn($filesystem);

        $this->app->shouldReceive('offsetExists')->with('blade.compiler.min')
            ->andReturn(true);

        $this->app->shouldReceive('offsetGet')->with('blade.compiler.min')
            ->andThrow(Exception::class, 'Test Succeeded!');
    }
}

interface LaravelApplication extends Application, ArrayAccess
{
}