<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 6:09
 */

namespace Dutchlabelshop\Parser;

use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Interfaces\RuleInterface;

abstract class RuleAbstract implements RuleInterface
{

    /** @var RuleInterface */
    private $rule;

    final public function executeRule(Context $context): bool
    {
        if (null === $this->rule) {
            return true;
        }

        return $this->rule->parse($context);
    }

    final public function addRule(RuleInterface $rule)
    {
        $this->rule = $rule;
    }

    final public function match(Context $context): bool
    {
        return $this->getMatcher()
                ->validate($context);
    }

    abstract public function getMatcher(): MatcherInterface;

}
