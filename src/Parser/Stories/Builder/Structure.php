<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-12-18
 * Time: 15:15
 */

namespace Elgentos\Parser\Stories\Builder;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\GeneralException;
use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Matcher\Exact;
use Elgentos\Parser\Rule\Callback;
use Elgentos\Parser\Rule\Factory;
use Elgentos\Parser\Rule\Iterate;
use Elgentos\Parser\Rule\RuleMatch;
use Elgentos\Parser\Rule\MergeDown;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;
use mysql_xdevapi\Exception;

class Structure implements StoriesInterface
{

    /** @var array */
    private $factories;
    /** @var StoryMetrics */
    private $storyMetrics;
    /** @var Story */
    private $story;

    /**
     * Objects constructor.
     *
     * @param array $factories
     */
    public function __construct(array $factories = [])
    {
        $this->factories = array_map(function (Factory $factory) {
            return $factory;
        }, $factories);

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
        return $this->storyMetrics->createStory(
                '0-root',
                $this->objectStory()
        );
    }

    protected function objectStory(): Story
    {
        return $this->storyMetrics->createStory(
                '1-builder',
                new Callback(function (Context $context) {
                    return $this->objectCallback($context);
                })
        );
    }

    protected function objectCallback(Context $context): bool
    {
        $current = &$context->getCurrent();

        if (! isset($current['factory'])) {
            throw new GeneralException('You have to define a factory for this');
        }

        $parser = $this->getFactory($current['factory']);

        if (isset($current['children'])) {
            $childContext = new Context($current['children']);
            $stories = array_map(function ($index) use ($childContext) {
                $childContext->setIndex($index);
                $this->objectCallback($childContext);

                return new Iterate(
                    new RuleMatch(
                        new Exact((string)$index, 'getIndex'),
                        $childContext->getCurrent()
                    ),
                    false
                );
            }, array_keys($current['children']));

            $stories[] = $parser;
            $parser = new Story('', ...$stories);
        }

        if (isset($current['multiple'])) {
            $parser = new Iterate($parser, false);
        }

        // Update current
        $current = $parser;

        return true;
    }

    protected function getFactory($factory)
    {
        if (! is_array($factory)) {
            return $this->getDefinedFactory($factory);
        }

        $class = $factory['class'];
        $arguments = $factory['arguments'] ?? null;
        $setters = $factory['setters'] ?? null;
        $singleton = $factory['singleton'] ?? false;

        return new Factory($class, $arguments, $setters, $singleton);
    }

    protected function getDefinedFactory($factory)
    {
        if (! isset($this->factories[$factory])) {
            throw new GeneralException("Class `{$factory}` is not defined.");
        }

        return $this->factories[$factory];
    }
}
