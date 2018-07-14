<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 22:16
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use Dutchlabelshop\Parser\RuleAbstract;

class SetIndex extends RuleAbstract
{

    /** @var string */
    private $index;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(string $index, MatcherInterface $matcher = null)
    {
        $this->index = $index;
        $this->matcher = $matcher ?? new IsTrue;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function parse(Context $context): bool
    {
        if (! $this->match($context)) {
            return false;
        }

        $context->setIndex($this->index);
        return true;
    }

}
