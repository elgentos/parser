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

class LoopAny extends RuleAbstract
{

    /** @var IsTrue */
    private $matcher;
    /** @var RuleInterface[] */
    private $rules;

    public function __construct(RuleInterface ...$rules)
    {
        if (func_num_args() < 2) {
            throw new \InvalidArgumentException("Should at least have two rules");
        }

        $this->matcher = new IsTrue;
        $this->rules = $rules;
    }

    public function parse(Context $context): bool
    {
        foreach ($this->rules as $rule) {
            if ($rule->parse($context)) {
                return $this->parse($context);
            }
        }

        // Stop if all rules fail
        return false;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

}
