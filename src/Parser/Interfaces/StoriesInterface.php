<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 9:57
 */

namespace Elgentos\Parser\Interfaces;

use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;

interface StoriesInterface
{

    /**
     * Get Story
     *
     * @return Story
     */
    public function getStory(): Story;

    /**
     * Get Story metrics
     *
     * @return StoryMetrics
     */
    public function getMetrics(): StoryMetrics;
}
