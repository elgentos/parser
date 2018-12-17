<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 10:59
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Exceptions\DebugNoLastStoryException;
use Elgentos\Parser\Interfaces\ParserInterface;
use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Stories\Factory;

class Debug implements ParserInterface
{

    /** @var StoriesInterface */
    private $lastStory;

    /**
     * @inheritdoc
     */
    public function parse(array &$data, StoriesInterface $stories)
    {
        $context = new Context($data);
        $story = $stories->getStory();

        $story->parse($context);

        $this->lastStory = $stories;
    }

    /**
     * Get last story to retrieve metrics
     *
     * @return StoriesInterface
     * @throws DebugNoLastStoryException
     */
    public function getLastStory(): StoriesInterface
    {
        if (! $this->lastStory) {
            throw new DebugNoLastStoryException;
        }

        return $this->lastStory;
    }

}
