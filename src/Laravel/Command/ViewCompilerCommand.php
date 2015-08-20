<?php

namespace ArjanSchouten\HTMLMin\Laravel\Command;

use ArjanSchouten\HTMLMin\MinifyContext;
use ArjanSchouten\HTMLMin\PlaceholderContainer;
use ArjanSchouten\HTMLMin\Placeholders\Blade\BladePlaceholder;
use ArjanSchouten\HTMLMin\ProvidesConstants;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class ViewCompilerCommand extends Command
{
    protected $name = 'minify:views';

    protected $description = 'Minify all the blade templates and save the templates';

    /**
     * Fire event and compile and minify the views.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('Going to minify you\'re views. Just a few seconds...');

        $this->setupCompiler();

        $this->compileViews();

        $this->info('Yeah! You\'re views are minified!');
    }

    protected function setupCompiler()
    {
        Blade::extend(function ($value, $compiler) {
            BladePlaceholder::setBladeTags($this->getBladeTags($compiler));

            $context = new MinifyContext(new PlaceholderContainer());

            return $this->laravel->make('blade.compiler.min')->run($context->setContents($value), $this->option())->getContents();
        });
    }

    /**
     * Get the blade tags which might be overruled by user.
     *
     * @param \Illuminate\View\Compilers\BladeCompiler $bladeCompiler
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
     * Find and compile all the views.
     *
     * @return void
     */
    protected function compileViews()
    {
        foreach ($this->laravel['view']->getFinder()->getPaths() as $path) {
            foreach ($this->laravel['files']->allFiles($path) as $file) {
                try {
                    $this->compileView($file);
                } catch (InvalidArgumentException $e) {
                    continue;
                }
            }
        }
    }

    /**
     * Compile and minify the given view file.
     *
     * @param string $file
     */
    protected function compileView($file)
    {
        $engine = $this->laravel['view']->getEngineFromPath($file);

        if ($engine instanceof CompilerEngine) {
            $engine->getCompiler()->compile($file);
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                ProvidesConstants::ATTRIBUTE_QUOTES,
                null,
                InputOption::VALUE_NONE,
                'Remove quotes around html attributes',
            ],
            [
                ProvidesConstants::REMOVE_DEFAULTS,
                null,
                InputOption::VALUE_NONE,
                'Remove defaults such as from <script type=text/javascript>',
            ],
            [
                ProvidesConstants::EMPTY_ATTRIBUTES,
                null,
                InputOption::VALUE_NONE,
                'Remove empty attributes. HTML boolean attributes are skipped',
            ],
            [
                ProvidesConstants::ALL,
                'a',
                InputOption::VALUE_NONE,
                'Use all the minification rules available',
            ],
        ];
    }
}
