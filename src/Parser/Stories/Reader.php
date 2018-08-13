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
use Elgentos\Parser\Rule\Match;
use Elgentos\Parser\Rule\MergeDown;
use Elgentos\Parser\Rule\MergeUp;
use Elgentos\Parser\Rule\NoLogic;
use Elgentos\Parser\Rule\Rename;
use Elgentos\Parser\Rule\Trim;
use Elgentos\Parser\Rule\Xml;
use Elgentos\Parser\Rule\Yaml;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;

class Reader implements StoriesInterface
{
    /** @var StoryMetrics */
    private $storyMetrics;
    /** @var Story */
    private $story;

    const IMPORT = '@import';

    const IMPORT_DIR = '@import-dir';

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
        return $this->getMetrics()->createStory(
            '-',
            new Changed(
                $this->getMetrics()->createStory(
                    '--root'
                    , $this->importStory($rootDir, false)
                    , $this->iterateStory($rootDir)
                    , $this->globStory($rootDir)
                )
            )
            , new MergeDown(false)
        );
    }

    protected function import(string $rootDir, string $pattern)
    {
        return new Match(
                new MatchAll(
                        new IsString,
                        new IsExact(self::IMPORT, 'getIndex'),
                        new IsRegExp($pattern)
                ),
                new Import($rootDir)
        );
    }

    protected function fromJson(string $rootDir, bool $merge)
    {
        return new LoopAll(
            $this->import($rootDir, '#\.json$#'),
            $this->getMetrics()->createStory(
                'import::json',
                new Json
            ),
            $merge ? new MergeDown(true) : new NoLogic(true)
        );
    }

    protected function fromText(string $rootDir)
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

    protected function fromYaml(string $rootDir, bool $merge)
    {
        return new LoopAll(
            $this->import($rootDir,'#\.ya?ml$#'),
            $this->getMetrics()->createStory(
                    'import::yaml',
                    new Yaml
            ),
            $merge ? new MergeDown(true) : new NoLogic(true)
        );
    }

    protected function fromCsv(string $rootDir, bool $merge)
    {
        return new LoopAll(
            $this->import($rootDir,'#\.csv$#'),
            $this->getMetrics()->createStory(
                'import::csv',
                new Trim,
                new Explode,
                new Csv(true),
                $merge ? new MergeDown(true) : new NoLogic(true)
            )
        );
    }

    protected function fromXml(string $rootDir, bool $merge)
    {
        return new LoopAll(
            $this->import($rootDir,'#\.xml$#'),
            $this->getMetrics()->createStory(
                'import::csv',
                new Trim,
                new Xml,
                $merge ? new MergeDown(true) : new NoLogic(true)
            )
        );
    }

    protected function importStory(string $rootDir, bool $merge): Story
    {
        return $this->getMetrics()->createStory(
            'import',
            new LoopAny(
                $this->fromText($rootDir),
                $this->fromJson($rootDir, $merge),
                $this->fromYaml($rootDir, $merge),
                $this->fromXml($rootDir, $merge),
                $this->fromCsv($rootDir, $merge)
            )
        );
    }

    protected function iterateStory(string $rootDir): Story
    {
        return $this->getMetrics()->createStory(
            'iterate',
            new Iterate(
                $this->importStory($rootDir, true),
                true
            )
        );
    }

    protected function globStory(string $rootDir): Iterate
    {
        return new Iterate(
            new LoopAll(
                new Match(
                    new MatchAll(
                        new IsString,
                        new IsExact(self::IMPORT_DIR, 'getIndex')
                    )
                ),
                new Glob($rootDir),
                $this->getMetrics()->createStory(
                    'glob',
                    new Iterate(
                        new LoopAll(
                            new Rename(self::IMPORT),
                            $this->importStory($rootDir, true)
                        ),
                        false
                    ),
                    new MergeUp(true)
                )
            ),
            true
        );
    }

}
