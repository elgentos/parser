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

class Iterate extends RuleAbstract
{
    /** @var bool */
    private $recursive;
    /** @var MatcherInterface */
    private $matcher;
    /** @var RuleInterface */
    private $nextRule;

    public function __construct(bool $recursive = false, RuleInterface $nextRule = null, MatcherInterface $matcher = null)
    {
        $this->recursive = $recursive;
        $this->nextRule = $nextRule;
        $this->matcher = $matcher ?? new IsTrue;
    }

    public function parse(Context $context): bool
    {
        if (! $this->match($context)) {
            return false;
        }

        $root = &$context->getRoot();
        foreach ($root as $key => &$value) {
            $context->setIndex((string)$key);

            if ($this->executeRule($context)) {
                continue;
            }

            if ($this->recursive && is_array($value)) {
                $this->parse(new Context($value));
                continue;
            }
        }

        return true;
    }


    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    private function executeRule(Context $context): bool
    {
        if (null === $this->nextRule) {
            return false;
        }

        return $this->nextRule->parse($context);
    }

}
