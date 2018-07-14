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

class Changed implements RuleInterface
{

    /** @var RuleInterface */
    private $rule;
    /** @var int */
    private $counter = 0;

    public function __construct(RuleInterface $rule)
    {
        $this->rule = $rule;
    }

    public function parse(Context $context): bool
    {
        $this->counter++;

        $this->rule->parse($context);
        if (! $this->match($context)) {
            return false;
        }

        // Again
        $context = new Context($context->getRoot());
        return $this->parse($context);
    }

    public function getCounter(): int
    {
        return $this->counter;
    }

    public function match(Context $context): bool
    {
        return $context->isChanged();
    }

}
