<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 0:13
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use Elgentos\Parser\Matcher\IsArray;

class MergeDown extends RuleAbstract
{

    /** @var bool */
    private $mergeRecursive;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(bool $mergeRecursive, MatcherInterface $matcher = null)
    {
        $this->mergeRecursive = $mergeRecursive;
        $this->matcher = $matcher ?? new IsArray;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function execute(Context $context): bool
    {
        $root = &$context->getRoot();

        $index = $context->getIndex();
        $content = $context->getCurrent();
        unset($root[$index]);

        $root = $this->merge($content, $root);
        $context->changed();

        reset($root);
        $context->setIndex((string)key($root));

        return true;
    }

    /**
     * First call
     *
     * @param array $source
     * @param array $destination
     * @return array
     */
    protected function merge(array &$source, array &$destination): array
    {
        return $this->niceMerge($source, $destination);
    }

    /**
     * Recursive nice merge
     *
     * @param array $source
     * @param array $destination
     * @return array
     */
    private function niceMerge(array &$source, array &$destination): array
    {
        foreach ($destination as $key => &$value) {
            if (
                    ! isset($source[$key]) ||
                    !is_array($value) ||
                    ! $this->mergeRecursive
            ) {
                $source[$key] = &$value;
                continue;
            }

            $source[$key] = $this->niceMerge($source[$key], $value);
        }

        return $source;
    }

}
