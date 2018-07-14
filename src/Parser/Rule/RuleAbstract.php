<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 6:09
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Interfaces\RuleInterface;

abstract class RuleAbstract implements RuleInterface
{

    final public function match(Context $context): bool
    {
        return $this->getMatcher()
                ->validate($context);
    }

    abstract public function getMatcher(): MatcherInterface;

    final public function parse(Context $context): bool
    {
        if (! $this->match($context)) {
            return false;
        }

        return $this->execute($context);
    }

    abstract public function execute(Context $context): bool;

}
