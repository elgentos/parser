<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 21:23
 */

namespace Dutchlabelshop\Parser\Rule;


use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsTrue;

class Rename extends RuleAbstract
{

    /** @var string */
    private $newIndex;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(string $newIndex, MatcherInterface $matcher = null)
    {
        $this->newIndex = $newIndex;
        $this->matcher = $matcher ?? new IsTrue;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function execute(Context $context): bool
    {
        $root = &$context->getRoot();

        $current = $context->getCurrent();
        unset($root[$context->getIndex()]);
        $root[$this->newIndex] = $current;

        $context->changed();

        return true;
    }

}
