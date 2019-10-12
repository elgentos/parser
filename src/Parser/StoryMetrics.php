<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 15:44
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Interfaces\RuleInterface;

class StoryMetrics
{

    /** @var []Story */
    private $stories = [];
    /** @var int */
    private $storyCount = 0;

    /**
     * Add story to book
     *
     * @param []Story ...$stories
     */
    public function addStories(Story ...$stories): void
    {
        \array_walk($stories, function ($story) {
            $this->stories[] = $story;
            $this->storyCount++;
        });
    }

    /**
     * Create a story from rules and add to book
     *
     * @param string $name
     * @param RuleInterface ...$rules
     * @return Story
     */
    public function createStory(string $name, RuleInterface ...$rules): Story
    {
        $story = new Story($name, ...$rules);
        $this->addStories($story);

        return $story;
    }

    /**
     * Tell how many stories are in the book
     *
     * @return int
     */
    public function getStories(): int
    {
        return $this->storyCount;
    }

    protected function getMetric(string $metric): float
    {
        return \array_reduce($this->stories, function ($cnt, Story $story) use ($metric) {
            return $cnt + $story->{$metric}();
        }, 0);
    }

    /**
     * Tell how many pages in the book
     *
     * @return int
     */
    public function getPages(): int
    {
        return (int)$this->getMetric('getPages');
    }

    /**
     * Tell how often stories are read
     *
     * @return int
     */
    public function getRead(): int
    {
        return (int)$this->getMetric('getRead');
    }

    /**
     * Tell how many pages where successful
     *
     * @return int
     */
    public function getSuccessful(): int
    {
        return (int)$this->getMetric('getSuccessful');
    }

    /**
     * Tell how much time in all stories in ms
     *
     * @return float
     */
    public function getCost(): float
    {
        return $this->getMetric('getCost');
    }

    public function getStatistics(
            string $message = '"%s" has %d page(s) and are read %d of %d time(s) successfully (%01.3fms)'
    ): array {
        $sortedStories = $this->getStoriesSortedByName();

        $storyStatistics = \array_map(function (Story $story) use (&$message) {
            return \sprintf(
                    $message,
                    $story->getName(),
                    $story->getPages(),
                    $story->getSuccessful(),
                    $story->getRead(),
                    $story->getCost()
            );
        }, $sortedStories);

        return $storyStatistics;
    }

    /**
     * Sort stories by name
     *
     * @return array
     */
    private function getStoriesSortedByName(): array
    {
        $stories = $this->stories;
        \usort($stories, function (Story $storyA, Story $storyB) {
            return $storyA->getName() <=> $storyB->getName();
        });

        return $stories;
    }
}
