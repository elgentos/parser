<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 1:16
 */

namespace Elgentos\Parser\Stories;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;
use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Matcher\IsArray;
use Elgentos\Parser\Matcher\IsExact;
use Elgentos\Parser\Matcher\IsRegExp;
use Elgentos\Parser\Matcher\IsString;
use Elgentos\Parser\Matcher\MatchAll;
use Elgentos\Parser\Rule\Callback;
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

    const PREFIX = '@--';
    const IMPORT = '@import';
    const IMPORT_DIR = '@import-dir';

    /** @var Story */
    private $importStoryCheckIndex;
    /** @var Story */
    private $importStoryNoCheckIndex;

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
                '1-root',
                $this->filesStory($rootDir),
                $this->mergeStory(),
                $this->finalStory()
        );
    }

    protected function import(string $rootDir, string $pattern, bool $checkIndex): RuleInterface
    {
        return new Match(
                new MatchAll(
                        $checkIndex ? new MatchAll(
                                new IsString,
                                new IsExact(self::IMPORT, 'getIndex')
                        ) : new IsString,
                        new IsRegExp($pattern)
                ),
                new Import($rootDir)
        );
    }

    protected function fromJson(string $rootDir, bool $checkIndex): RuleInterface
    {
        return new LoopAll(
                $this->import($rootDir, '#\.json$#', $checkIndex),
                $this->getMetrics()->createStory(
                        self::IMPORT . '::json' . ($checkIndex ? '+' : '-'),
                        new Json,
                        $checkIndex ? new Rename(self::PREFIX . self::IMPORT) : new NoLogic(true)
                )
        );
    }

    protected function fromText(string $rootDir, bool $checkIndex): RuleInterface
    {
        return new LoopAll(
                $this->import($rootDir, '#\.txt$#', $checkIndex),
                $this->getMetrics()->createStory(
                        self::IMPORT . '::text' . ($checkIndex ? '+' : '-'),
                        new Trim,
                        $checkIndex ? new Rename(self::PREFIX . self::IMPORT) : new NoLogic(true)
                )
        );
    }

    protected function fromYaml(string $rootDir, bool $checkIndex): RuleInterface
    {
        return new LoopAll(
                $this->import($rootDir, '#\.ya?ml$#', $checkIndex),
                $this->getMetrics()->createStory(
                        self::IMPORT . '::yaml' . ($checkIndex ? '+' : '-'),
                        new Yaml,
                        $checkIndex ? new Rename(self::PREFIX . self::IMPORT) : new NoLogic(true)
                )
        );
    }

    protected function fromCsv(string $rootDir, bool $checkIndex): RuleInterface
    {
        return new LoopAll(
                $this->import($rootDir, '#\.csv$#', $checkIndex),
                $this->getMetrics()->createStory(
                        self::IMPORT . '::csv' . ($checkIndex ? '+' : '-'),
                        new Trim,
                        new Explode,
                        new Csv(true),
                        $checkIndex ? new Rename(self::PREFIX . self::IMPORT) : new NoLogic(true)
                )
        );
    }

    protected function fromXml(string $rootDir, bool $checkIndex): RuleInterface
    {
        return new LoopAll(
                $this->import($rootDir, '#\.xml$#', $checkIndex),
                $this->getMetrics()->createStory(
                        self::IMPORT . '::xml' . ($checkIndex ? '+' : '-'),
                        new Trim,
                        new Xml,
                        $checkIndex ? new Rename(self::PREFIX . self::IMPORT) : new NoLogic(true)
                )
        );
    }

    protected function importStory(string $rootDir, bool $checkIndex): RuleInterface
    {
        if ($checkIndex && null !== $this->importStoryCheckIndex) {
            return $this->importStoryCheckIndex;
        } elseif (!$checkIndex && null !== $this->importStoryNoCheckIndex) {
            return $this->importStoryNoCheckIndex;
        }

        $importStory = $this->getMetrics()
                ->createStory(
                        self::IMPORT . ($checkIndex ? '+' : '-'),
                        $this->fromText($rootDir, $checkIndex)
                        , $this->fromJson($rootDir, $checkIndex)
                        , $this->fromXml($rootDir, $checkIndex)
                        , $this->fromYaml($rootDir, $checkIndex)
                        , $this->fromCsv($rootDir, $checkIndex)
                );

        $checkIndex
                && ($this->importStoryCheckIndex = $importStory)
                || ($this->importStoryNoCheckIndex = $importStory);

        return $importStory;
    }

    protected function iterateStory(string $rootDir): RuleInterface
    {
        return $this->getMetrics()->createStory(
                '1.1.1-iterate',
                new Iterate(
                        new LoopAny(
                            $this->importStory($rootDir, true),
                            $this->globStory($rootDir)
                        ),
                        true
                )
        );
    }

    protected function globStory(string $rootDir): RuleInterface
    {
        $isCsv = true;
        $csvIsBefore = new Match(new IsRegExp('#\.csv$#'));
        $csvIsAfter = new Match(new IsArray);

        return new LoopAll(
                new Match(
                        new MatchAll(
                                new IsString,
                                new IsExact(self::IMPORT_DIR, 'getIndex')
                        )
                ),
                $this->getMetrics()->createStory(
                        self::IMPORT_DIR,
                        new Callback(function() use (&$isCsv) {
                            $isCsv = true;
                            return true;
                        }),
                        new Glob($rootDir),
                        new Iterate(
                                $this->getMetrics()->createStory(
                                        self::IMPORT_DIR . '::iterate',
                                        new Callback(function(Context $context) use (&$isCsv, $csvIsBefore) {
                                            if (! $isCsv) {
                                                return false;
                                            }

                                            return $isCsv = $csvIsBefore->parse($context);
                                        }),
                                        $this->importStory($rootDir, false),
                                        new Callback(function(Context $context) use (&$isCsv, $csvIsAfter) {
                                            if (! $isCsv) {
                                                return false;
                                            }

                                            return $isCsv = $csvIsAfter->parse($context);
                                        })
                                ),
                                false
                        ),
                        new Callback(function(Context $context) use (&$isCsv) {
                            if (! $isCsv) {
                                return false;
                            }

                            $current = &$context->getCurrent();
                            $current = [array_merge(...$current)];

                            return true;
                        })
                ),
                new Rename(self::PREFIX . self::IMPORT_DIR)
        );
    }

    protected function filesStory(string $rootDir): RuleInterface
    {
        return new Changed(
                $this->getMetrics()->createStory(
                        '1.1-files'
                        , $this->importStory($rootDir, true)
                        , $this->iterateStory($rootDir)
                )
        );
    }

    protected function mergeStory(): RuleInterface
    {
        return new Changed(
                $this->getMetrics()->createStory(
                        '1.2-merge',
                        new Iterate(
                                new LoopAny(
                                        $this->mergeImport(),
                                        $this->mergeText(),
                                        $this->mergeGlob()
                                ),
                                true
                        )
                )
        );
    }

    protected function mergeImport(): RuleInterface
    {
        return new Match(
                new MatchAll(
                        new IsArray,
                        new IsExact(self::PREFIX . self::IMPORT, 'getIndex')
                ),
                $this->getMetrics()
                        ->createStory(
                                '1.2.1-import',
                                new MergeDown(true)
                        )
        );
    }

    protected function mergeText(): RuleInterface
    {
        return new Match(
                new MatchAll(
                        new IsString,
                        new IsExact(self::PREFIX . self::IMPORT, 'getIndex')
                ),
                $this->getMetrics()
                        ->createStory(
                                '1.2.2-text',
                                new Rename('text')
                        )
        );
    }

    protected function mergeGlob(): RuleInterface
    {
        return new Match(
                new MatchAll(
                        new IsArray,
                        new IsExact(self::PREFIX . self::IMPORT_DIR, 'getIndex')
                ),
                $this->getMetrics()->createStory(
                        '1.2.3-glob',
                        new Iterate(
                                new Match(
                                        new IsArray,
                                        new MergeUp(true)
                                ),
                                false
                        ),
                        new MergeDown(false)
                )
        );
    }

    protected function finalStory(): RuleInterface
    {
        return $this->getMetrics()
                ->createStory(
                        '1.3-final',
                        new MergeDown(false)
                );
    }

}
