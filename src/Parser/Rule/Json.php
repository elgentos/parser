<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:25
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\RuleAbstract;

class Json extends RuleAbstract
{
    /** @var bool */
    private $mergeRecursive;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(bool $mergeRecursive = false, MatcherInterface $matcher = null)
    {
        $this->mergeRecursive = $mergeRecursive;
        $this->matcher = $matcher ?? new IsExact('__json');
    }

    public function parse(Context $context): bool
    {
        if (! $this->match($context)) {
            return false;
        }

        $root = &$context->getRoot();
        $jsonData = $context->getCurrent();
        unset($root[$context->getIndex()]);

        $content = json_decode($jsonData, true);
        $root = $this->niceMerge($content, $root);

        reset($root);
        $context->setIndex(key($root));
        $context->changed();

        return true;
    }

    /**
     * Recursive nice merge
     *
     * @param array $result
     * @param array $new
     * @return array
     */
    protected function niceMerge(array $result, array $new): array
    {
        foreach ($new as $key => &$value) {
            if (
                    ! isset($result[$key]) ||
                    !is_array($value) ||
                    ! $this->mergeRecursive
            ) {
                $result[$key] = $value;
                continue;
            }

            $result[$key] = $this->niceMerge($result[$key], $value);
        }

        return $result;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

}
