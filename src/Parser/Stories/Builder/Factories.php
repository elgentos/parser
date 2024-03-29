<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-12-18
 * Time: 15:13
 */

namespace Elgentos\Parser\Stories\Builder;

use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Matcher\IsArray;
use Elgentos\Parser\Rule\Factory;
use Elgentos\Parser\Rule\Iterate;
use Elgentos\Parser\Rule\RuleMatch;
use Elgentos\Parser\Rule\MergeDown;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;

class Factories implements StoriesInterface
{

    /** @var StoryMetrics */
    private $storyMetrics;
    /** @var Story */
    private $story;

    /** @var bool */
    private $singleton;

    /**
     * Factories constructor.
     * @param bool $singleton
     */
    public function __construct($singleton = false)
    {
        $this->singleton = $singleton;

        $this->storyMetrics = new StoryMetrics;
        $this->story = $this->initStory();
    }


    /**
     * Get Story
     *
     * @return Story
     */
    public function getStory(): Story
    {
        return $this->story;
    }

    /**
     * Get Story metrics
     *
     * @return StoryMetrics
     */
    public function getMetrics(): StoryMetrics
    {
        return $this->storyMetrics;
    }

    protected function initStory(): Story
    {
        return $this->storyMetrics
                ->createStory(
                        '0-root',
                        $this->iterateStory(),
                        $this->finalStory()
                );
    }

    /**
     * @return Story
     * @throws \ReflectionException
     */
    protected function iterateStory(): Story
    {
        return $this->storyMetrics->createStory(
                '1-iterate',
                new Iterate(
                        new RuleMatch(
                                new IsArray,
                                $this->factoryStory()
                        ),
                        false
                )
        );
    }

    /**
     * @return Story
     * @throws \ReflectionException
     */
    protected function factoryStory(): Story
    {
        return $this->storyMetrics->createStory(
            '2-factory',
            new Factory(Factory::class, [
                'class' => null,
                'arguments' => null,
                'setters' => [],
                'singleton' => $this->singleton
            ])
        );
    }

    protected function finalStory(): Story
    {
        return $this->storyMetrics->createStory(
            '3-final',
            new MergeDown(false)
        );
    }
}
