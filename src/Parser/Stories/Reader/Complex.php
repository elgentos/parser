<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 1:16
 */

namespace Elgentos\Parser\Stories\Reader;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;
use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Matcher\EndsWith;
use Elgentos\Parser\Matcher\IsArray;
use Elgentos\Parser\Matcher\Exact;
use Elgentos\Parser\Matcher\IsString;
use Elgentos\Parser\Matcher\All;
use Elgentos\Parser\Matcher\ResolveTrue;
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
use Elgentos\Parser\Rule\RuleMatch;
use Elgentos\Parser\Rule\MergeDown;
use Elgentos\Parser\Rule\MergeUp;
use Elgentos\Parser\Rule\NoLogic;
use Elgentos\Parser\Rule\Rename;
use Elgentos\Parser\Rule\Trim;
use Elgentos\Parser\Rule\Xml;
use Elgentos\Parser\Rule\Yaml;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;

class Complex implements StoriesInterface
{
    /** @var StoryMetrics */
    private $storyMetrics;
    /** @var Story */
    private $story;

    const PREFIX = '@--';
    const IMPORT = '@import';
    const IMPORT_DIR = '@import-dir';

    /** @var Story */
    private $importStory;

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
            '0-root',
            $this->filesStory($rootDir),
            $this->mergeStory(),
            $this->finalStory()
        );
    }

    protected function import(string $rootDir, string $pattern, bool $checkIndex): RuleInterface
    {
        return new RuleMatch(
            new All(
                new IsString,
                $checkIndex
                    ? new Exact(self::IMPORT, 'getIndex')
                    : new ResolveTrue,
                new EndsWith($pattern)
            ),
            new Import($rootDir)
        );
    }

    protected function fromJson(string $rootDir, bool $checkIndex): RuleInterface
    {
        return new LoopAll(
            $this->import($rootDir, '.json', $checkIndex),
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
            $this->import($rootDir, '.txt', $checkIndex),
            $this->getMetrics()->createStory(
                self::IMPORT . '::text' . ($checkIndex ? '+' : '-'),
                new Trim,
                $checkIndex ? new Rename(self::PREFIX . self::IMPORT) : new NoLogic(true)
            )
        );
    }

    protected function fromYaml(string $rootDir, bool $checkIndex): RuleInterface
    {
        /** @codeCoverageIgnoreStart */
        if (! \class_exists('\Symfony\Component\Yaml\Yaml')) {
            return new NoLogic(false);
        }
        /** @codeCoverageIgnoreEnd */

        return new LoopAll(
            $this->import($rootDir, '.yaml', $checkIndex),
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
            $this->import($rootDir, '.csv', $checkIndex),
            $this->getMetrics()->createStory(
                self::IMPORT . '::csv' . ($checkIndex ? '+' : '-'),
                new Trim,
                new Csv(true, ',', '"', '""'),
                $checkIndex ? new Rename(self::PREFIX . self::IMPORT) : new NoLogic(true)
            )
        );
    }

    protected function fromXml(string $rootDir, bool $checkIndex): RuleInterface
    {
        return new LoopAll(
            $this->import($rootDir, '.xml', $checkIndex),
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
        // @codeCoverageIgnoreStart
        $index = $checkIndex ? 0 : 1;
        if (isset($this->importStory[$index])) {
            return $this->importStory[$index];
        }
        // @codeCoverageIgnoreEnd

        $importStory = $this->getMetrics()
            ->createStory(
                self::IMPORT . ($checkIndex ? '+' : '-'),
                $this->fromText($rootDir, $checkIndex),
                $this->fromJson($rootDir, $checkIndex),
                $this->fromXml($rootDir, $checkIndex),
                $this->fromYaml($rootDir, $checkIndex),
                $this->fromCsv($rootDir, $checkIndex)
            );

        $this->importStory[$index] = $importStory;
        return $importStory;
    }

    protected function iterateStory(string $rootDir): RuleInterface
    {
        return $this->getMetrics()->createStory(
            '1.1-iterate',
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
        $csvIsBefore = new RuleMatch(new EndsWith('.csv'));
        $csvIsAfter = new RuleMatch(new IsArray);

        return new LoopAll(
            new RuleMatch(
                new All(
                    new IsString,
                    new Exact(self::IMPORT_DIR, 'getIndex')
                )
            ),
            $this->getMetrics()->createStory(
                self::IMPORT_DIR,
                new Callback(function () use (&$isCsv) {
                    $isCsv = true;
                    return true;
                }),
                new Glob($rootDir),
                new Iterate(
                    $this->getMetrics()->createStory(
                        self::IMPORT_DIR . '::iterate',
                        new Callback(function (Context $context) use (&$isCsv, $csvIsBefore) {
                            if (! $isCsv) {
                                return false;
                            }

                            return $isCsv = $csvIsBefore->parse($context);
                        }),
                        $this->importStory($rootDir, false),
                        new Callback(function (Context $context) use (&$isCsv, $csvIsAfter) {
                            if (! $isCsv) {
                                return false;
                            }

                            return $isCsv = $csvIsAfter->parse($context);
                        })
                    ),
                    false
                ),
                new Callback(function (Context $context) use (&$isCsv) {
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
        return $this->getMetrics()->createStory(
            '1-files', $this->importStory($rootDir, true), $this->iterateStory($rootDir)
        );
    }

    protected function mergeStory(): RuleInterface
    {
        return new Changed(
            $this->getMetrics()->createStory(
                '2-merge',
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
        return new RuleMatch(
            new All(
                new IsArray,
                new Exact(self::PREFIX . self::IMPORT, 'getIndex')
            ),
            $this->getMetrics()
                ->createStory(
                    '2.1-import',
                    new MergeDown(true)
                )
        );
    }

    protected function mergeText(): RuleInterface
    {
        return new RuleMatch(
            new All(
                new IsString,
                new Exact(self::PREFIX . self::IMPORT, 'getIndex')
            ),
            $this->getMetrics()
                ->createStory(
                    '2.2-text',
                    new Rename('text')
                )
        );
    }

    protected function mergeGlob(): RuleInterface
    {
        return new RuleMatch(
            new All(
                new IsArray,
                new Exact(self::PREFIX . self::IMPORT_DIR, 'getIndex')
            ),
            $this->getMetrics()->createStory(
                '2.3-glob',
                new Iterate(
                    new RuleMatch(
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
                '3-final',
                new MergeDown(false)
            );
    }
}
