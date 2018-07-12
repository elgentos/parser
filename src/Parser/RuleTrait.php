<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 6:09
 */

namespace Dutchlabelshop\Parser;

use Dutchlabelshop\Parser\Interfaces\RuleInterface;

trait RuleTrait
{

    /** @var RuleInterface */
    private $rule;

    public function executeRule(Context $context): bool
    {
        if (null === $this->rule) {
            return false;
        }

        return $this->rule->parse($context);
    }

    public function addRule(RuleInterface $rule)
    {
        $this->rule = $rule;
    }

}
