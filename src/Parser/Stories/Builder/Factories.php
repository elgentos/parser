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
use Elgentos\Parser\Rule\Match;
use Elgentos\Parser\Rule\NoLogic;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;

class Factories implements StoriesInterface
{

    /** @var StoryMetrics */
    private $storyMetrics;
    /** @var Story */
    private $story;

    public function __construct()
    {
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

    private function initStory(): Story
    {
        return $this->storyMetrics
                ->createStory(
                        '0-root',
                        $this->iterateStory()
                );
    }

    /**
     * @return Story
     * @throws \ReflectionException
     */
    private function iterateStory(): Story
    {
        return $this->storyMetrics->createStory(
                '1-iterate',
                new Iterate(
                        new Match(
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
    private function factoryStory(): Story
    {
        return $this->storyMetrics->createStory(
            '2-factory',
            new Factory(Factory::class, ['class', 'arguments', 'setters'])
        );
    }

}