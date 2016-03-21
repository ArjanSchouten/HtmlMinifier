<?php

namespace ArjanSchouten\HtmlMinifier\Laravel\Command;

use ArjanSchouten\HtmlMinifier\Measurements\ReferencePoint;
use ArjanSchouten\HtmlMinifier\MinifyContext;
use ArjanSchouten\HtmlMinifier\Option;
use ArjanSchouten\HtmlMinifier\Options;
use ArjanSchouten\HtmlMinifier\PlaceholderContainer;
use ArjanSchouten\HtmlMinifier\Placeholders\Blade\BladePlaceholder;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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
     * @var \ArjanSchouten\HtmlMinifier\MinifyContext
     */
    protected $minifyContext;

    /**
     * Fire event and compile and minify the views.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('Going to minify your views. Just a few seconds...');

        $this->setupCompiler();

        $this->compileViews();

        $this->info('Yeah! Your views are minified!');

        $this->createMinifyOutput();
    }

    /**
     * Extend the blade compiler for the minification process.
     *
     * @return void
     */
    protected function setupCompiler()
    {
        Blade::extend(function ($value, $compiler) {
            BladePlaceholder::setBladeTags($this->getBladeTags($compiler));

            $context = new MinifyContext(new PlaceholderContainer());
            $minifier = $this->laravel->make('blade.compiler.min');
            $this->minifyContext = $minifier->run($context->setContents($value), $this->option());

            return $this->minifyContext->getContents();
        });
    }

    /**
     * Get the blade tags which might be overruled by user.
     *
     * @param \Illuminate\View\Compilers\BladeCompiler $bladeCompiler
     *
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

    protected function createMinifyOutput()
    {
        $measurements = $this->minifyContext->getMeasurement();
        $referencePoints = Collection::make($measurements->getReferencePoints());
        $totalBytesSavedPercentage = 0;

        $lastReferencePoint = null;
        $rows = $referencePoints->map(function (ReferencePoint $referencePoint) use (&$lastReferencePoint, &$totalBytesSavedPercentage) {
            $bytesSaved = '';
            $bytesSavedPercentage = '';
            if ($lastReferencePoint != null) {
                $bytesSaved = $lastReferencePoint->getKiloBytes() - $referencePoint->getKiloBytes();
                $totalBytesSavedPercentage += $bytesSavedPercentage = $this->calculateImprovementPercentage($referencePoint->getKiloBytes(), $lastReferencePoint->getKiloBytes());
                $bytesSavedPercentage = round(abs($bytesSavedPercentage),1).'%';
            }
            $lastReferencePoint = $referencePoint;

            return [$referencePoint->getName(), round($referencePoint->getKiloBytes(),1), round($bytesSaved,1), $bytesSavedPercentage];
        });

        $rows[] = [
            'Total',
            round($referencePoints->last()->getKiloBytes(),1),
            abs(round($referencePoints->last()->getKiloBytes() - $referencePoints->first()->getKiloBytes(),1)),
            abs(round($totalBytesSavedPercentage,1)).'%'
        ];

        $this->table(['Minification strategy', 'Size (KB)', 'Saved (KB)', 'Size (%)'], $rows);
    }

    private function calculateImprovementPercentage($new, $old)
    {
        return ($new - $old) / $old * 100;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = Collection::make(Options::options())
            ->map(function (Option $option) {
                return [
                    $option->getName(),
                    null,
                    InputOption::VALUE_NONE,
                    $option->getDescription(),
                ];
            })->all();

        $options[Options::ALL] = [
            Options::ALL,
            'a',
            InputOption::VALUE_NONE,
            'Use all the minification rules available',
        ];

        return $options;
    }
}
