<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 0:13
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsArray;

class Merge extends RuleAbstract
{

    /** @var bool */
    private $mergeRecursive;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(bool $mergeRecursive = false, MatcherInterface $matcher = null)
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

        $root = $this->niceMerge($content, $root);
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

}
