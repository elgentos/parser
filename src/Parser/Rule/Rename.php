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

class Rename extends RuleAbstract
{

    /** @var MatcherInterface */
    private $matcher;
    /** @var string */
    private $newIndex;

    public function __construct(MatcherInterface $matcher, string $newIndex)
    {
        $this->matcher = $matcher;
        $this->newIndex = $newIndex;
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
