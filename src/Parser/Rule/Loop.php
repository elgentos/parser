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
use Dutchlabelshop\Parser\Interfaces\RuleInterface;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use Dutchlabelshop\Parser\RuleAbstract;

class Loop extends RuleAbstract
{

    /** @var IsTrue */
    private $matcher;

    public function __construct(RuleInterface ...$rules)
    {
        if (func_num_args() < 2) {
            throw new \InvalidArgumentException("Should at least have two rules");
        }

        $firstRule = $lastRule = array_shift($rules);

        // I'll call first rule
        $this->addRule($firstRule);

        // All next rules will be linked
        while ($nextRule = array_shift($rules)) {
            $lastRule->addRule($nextRule);
            $lastRule = $nextRule;
        }

        // Last rule will call first rule, rules should implement a break system
        $lastRule->addRule($firstRule);

        $this->matcher = new IsTrue;
    }

    public function parse(Context $context): bool
    {
        return $this->executeRule($context);
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

}
