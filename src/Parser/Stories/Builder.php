<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-8-18
 * Time: 1:07
 */

namespace Elgentos\Parser\Stories;

use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;

class Builder implements StoriesInterface
{

    /** @var StoryMetrics */
    private $storyMetrics;
    /** @var Story */
    private $story;

    public function __construct()
    {
        $this->storyMetrics = new StoryMetrics;
        $this->story = $this->storyMetrics->createStory('name');
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
}
