<?php

namespace ArjanSchouten\HTMLMin\Laravel\Command;

use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\View\Engines\CompilerEngine;
use Symfony\Component\Console\Input\InputOption;
use ArjanSchouten\HTMLMin\Pipeline\BladePipeline;

class ViewCompilerCommand extends Command
{
    protected $name = 'minify:views';

    protected $description = 'Minify all the blade templates and save the templates';

    /**
     * Fire event and compile and minify the views.
     */
    public function fire()
    {
        $this->info('Going to minify you\'re views.');

        $this->compileViews();

        $this->info('Views are minified!');
    }

    /**
     * Find and compile all the views.
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

    protected function compileView($file)
    {
        $engine = $this->laravel['view']->getEngineFromPath($file);

        if ($engine instanceof CompilerEngine) {
            $this->laravel['blade.compiler.min']->buildPipeline(new BladePipeline(), $this->option());
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
                'remove-attributequotes',
                null,
                InputOption::VALUE_NONE,
                'Remove quotes around html attributes',
            ],
            [
                'remove-defaults',
                null,
                InputOption::VALUE_NONE,
                'Remove defaults such as from <script type=text/javascript>',
            ],
            [
                'remove-empty-attributes',
                null,
                InputOption::VALUE_NONE,
                'Remove empty attributes. HTML boolean attributes are skipped',
            ],
            [
                'all',
                'a',
                InputOption::VALUE_NONE,
                'Use all the minification rules available',
            ],
        ];
    }
}
