<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 1:16
 */

namespace Elgentos\Parser\Stories;

use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Matcher\IsExact;
use Elgentos\Parser\Matcher\IsRegExp;
use Elgentos\Parser\Matcher\IsString;
use Elgentos\Parser\Matcher\MatchAll;
use Elgentos\Parser\Rule\Changed;
use Elgentos\Parser\Rule\Csv;
use Elgentos\Parser\Rule\Explode;
use Elgentos\Parser\Rule\Glob;
use Elgentos\Parser\Rule\Import;
use Elgentos\Parser\Rule\Iterate;
use Elgentos\Parser\Rule\Json;
use Elgentos\Parser\Rule\LoopAll;
use Elgentos\Parser\Rule\LoopAny;
use Elgentos\Parser\Rule\MergeDown;
use Elgentos\Parser\Rule\MergeUp;
use Elgentos\Parser\Rule\Rename;
use Elgentos\Parser\Rule\Trim;
use Elgentos\Parser\Rule\Yaml;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;

class Reader implements StoriesInterface
{
    /** @var StoryMetrics */
    private $storyMetrics;
    /** @var Story */
    private $story;

    public function __construct(string $rootDir = '.')
    {
        $this->storyMetrics = new StoryMetrics;
        $this->story = $this->initStory($rootDir);
    }

    public function getStory(): Story
    {
        return $this->story;
    }

    public function getMetrics(): StoryMetrics
    {
        return $this->storyMetrics;
    }



    protected function initStory(string $rootDir): Story
    {
        $importStory = $this->getMetrics()->createStory(
            'import',
            new LoopAny(
                $this->toText($rootDir),
                $this->toJson($rootDir),
                $this->toYaml($rootDir),
                $this->toCsv($rootDir)
            )
        );

        $iterateStory = $this->getMetrics()->createStory(
            'iterate',
            new Iterate(
                $importStory,
                true
            )
        );

        $globStory = new Iterate(
            new LoopAll(
                new Glob(
                    $rootDir,
                    new MatchAll(
                        new IsString,
                        new IsExact('@import-glob', 'getIndex')
                    )
                ),
                $this->getMetrics()->createStory(
                    'glob',
                    new Iterate(
                        new LoopAll(
                            new Rename('@import'),
                            $importStory
                        ),
                        false
                    ),
                    new MergeUp(true)
                )
            ),
            true
        );

        return new Story(
            '--',
            new Changed(
                $this->getMetrics()->createStory(
                    '--root'
                    , $importStory
                    , $iterateStory
                    , $globStory
                )
            )
        );
    }

    protected function import(string $rootDir, string $pattern)
    {
        return new Import(
                $rootDir,
                new MatchAll(
                        new IsString,
                        new IsExact('@import', 'getIndex'),
                        new IsRegExp($pattern)
                )
        );
    }

    protected function toJson(string $rootDir)
    {
        return new LoopAll(
            $this->import($rootDir, '#\.json$#'),
            $this->getMetrics()->createStory(
                'import::json',
                new Json
            ),
            new MergeDown(true)
        );
    }

    protected function toText(string $rootDir)
    {
        return new LoopAll(
            $this->import($rootDir, '#\.txt$#'),
            $this->getMetrics()->createStory(
                'import::text',
                new Trim,
                new Rename('text')
            )
        );
    }

    protected function toYaml(string $rootDir)
    {
        return new LoopAll(
            $this->import($rootDir,'#\.ya?ml$#'),
            $this->getMetrics()->createStory(
                    'import::yaml',
                    new Yaml
            ),
            new MergeDown(true)
        );
    }

    protected function toCsv(string $rootDir)
    {
        return new LoopAll(
            $this->import($rootDir,'#\.csv$#'),
            $this->getMetrics()->createStory(
                'import::csv',
                new Trim,
                new Explode,
                new Csv(true),
                new MergeDown(true)
            )
        );
    }

}
