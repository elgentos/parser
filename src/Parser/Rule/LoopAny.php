<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:25
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;

class LoopAny implements RuleInterface
{

    /** @var RuleInterface[] */
    private $rules;

    public function __construct(RuleInterface ...$rules)
    {
        if (func_num_args() < 2) {
            throw new \InvalidArgumentException("Should at least have two rules");
        }

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

    public function match(Context $context): bool
    {
        return true;
    }

}
