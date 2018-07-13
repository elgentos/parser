<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-7-18
 * Time: 12:31
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Interfaces\RuleInterface;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\RuleAbstract;
use PHPUnit\Framework\MockObject\Builder\Match;

class Changed extends RuleAbstract
{

    /** @var RuleInterface */
    private $rule;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(RuleInterface $rule, MatcherInterface $matcher = null)
    {
        $this->rule = $rule;
        $this->matcher = $matcher ?? new IsExact(true, 'isChanged');
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function parse(Context $context): bool
    {
        $this->rule->parse($context);

        if (! $this->match($context)) {
            return false;
        }

        // Again
        $context = new Context($context->getRoot());
        return $this->parse($context);
    }

}
