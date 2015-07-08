<?php

namespace ArjanSchouten\HTMLMin\Laravel\Command;

use ArjanSchouten\HTMLMin\Minify;
use Event;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\View\Engines\CompilerEngine;
use Symfony\Component\Console\Input\InputOption;

class ViewCompilerCommand extends Command
{
    protected $name = 'minify:views';

    protected $description = 'Minify all the blade templates and save the templates';

    /**
     * Fire event and compile and minify the views.
     */
    public function fire()
    {
        $this->compileViews();
    }

    /**
     * Find and compile all the views.
     */
    protected function compileViews()
    {
        foreach ($this->laravel['view']->getFinder()->getPaths() as $path) {
            foreach ($this->laravel['files']->allFiles($path) as $file) {
                try {
                    $engine = $this->laravel['view']->getEngineFromPath($file);
                } catch (InvalidArgumentException $e) {
                    continue;
                }

                if ($engine instanceof CompilerEngine) {
                    $this->laravel['blade.compiler.min']->setOptions($this->option());
                    $engine->getCompiler()->compile($file);
                }
            }
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
                'aq',
                InputOption::VALUE_NONE,
                'Remove quotes around html attributes',
            ],
            [
                'remove-defaults',
                'd',
                InputOption::VALUE_NONE,
                'Remove defaults such as from <script type=text/javascript>',
            ],
            [
                'remove-empty-attributes',
                'e',
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
