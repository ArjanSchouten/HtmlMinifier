<?php
namespace ArjanSchouten\HTMLMin\Command;

use Event;
use Illuminate\Console\Command;
use Illuminate\View\Engines\CompilerEngine;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class BladeCompiler extends Command
{

    protected $name = 'minify:blade';

    protected $description = 'Minify all the blade templates and save the templates';

    /**
     * Fire event and compile and minify the views.
     */
    public function fire()
    {
        $minifiers = $this->getEnabledMinificationRules();
        $this->registerBladeMinifyEvent($minifiers);
        $this->compileViews();
    }

    /**
     * Get all the minifiers which are enabled or doesn't need enabling.
     *
     * @return array
     */
    protected function getEnabledMinificationRules()
    {
        if ($this->option('all')) {
            return $this->laravel->tagged('bladeMinifyRules');
        }
        $minifiers = [];
        foreach ($this->laravel->tagged('bladeMinifyRules') as $minificationRule) {
            if ($this->isEnabledMinificationRule($minificationRule)) {
                $minifiers[] = $minificationRule;
            }
        }

        return $minifiers;
    }

    /**
     * @param MinificationRule $minificationRule
     *
     * @return bool
     */
    protected function isEnabledMinificationRule($minificationRule)
    {
        if (!method_exists($minificationRule, 'minifyOnlyWhenCommandlinOptionProvided')) {
            return true;
        }
        $optionName = $minificationRule->minifyOnlyWhenCommandlinOptionProvided();
        if ($optionName == false) {
            return true;
        }

        return $this->option($optionName);
    }

    /**
     * Register an event listener which gives the active minifiers.
     *
     * @param array $minifiers
     */
    protected function registerBladeMinifyEvent(array $minifiers)
    {
        Event::listen('bladeMinify', function () use ($minifiers) {
            return $minifiers;
        });
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
                null,
                InputOption::VALUE_NONE,
                'Remove quotes around html attributes.'
            ],
            [
                'remove-default-types',
                null,
                InputOption::VALUE_NONE,
                'Remove default types such as from <script type=text/javascript>'
            ],
            [
                'all',
                null,
                InputOption::VALUE_NONE,
                'Use all the minification rules available'
            ]
        ];
    }
}