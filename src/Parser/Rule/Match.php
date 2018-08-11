<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-8-18
 * Time: 20:50
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use Elgentos\Parser\Interfaces\RuleInterface;

class Match implements RuleInterface
{

    /** @var MatcherInterface */
    private $matcher;
    /** @var RuleInterface */
    private $nextRule;

    public function __construct(MatcherInterface $matcher, RuleInterface $nextRule = null)
    {
        $this->matcher = $matcher;
        $this->nextRule = $nextRule;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function parse(Context $context): bool
    {
        return $this->match($context) && $this->next($context);
    }

    private function match(Context $context): bool
    {
        return $this->matcher->validate($context);
    }

    private function next(Context $context)
    {
        if (null === $this->nextRule) {
            return true;
        }

        return $this->nextRule->parse($context);
    }

}
