<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:25
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use Elgentos\Parser\Interfaces\RuleInterface;
use Elgentos\Parser\Matcher\IsArray;
use Elgentos\Parser\Matcher\IsTrue;

class Iterate extends RuleAbstract
{
    /** @var RuleInterface */
    private $rule;
    /** @var bool */
    private $recursive;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(RuleInterface $rule, bool $recursive, MatcherInterface $matcher = null)
    {
        $this->rule = $rule;
        $this->recursive = $recursive;
        $this->matcher = $matcher ?? new IsArray;
    }

    public function execute(Context $context): bool
    {
        return $this->recursive($context);
    }

    private function recursive(Context $context, $level = 0): bool
    {
        if ($this->rule->parse($context)) {
            return true;
        }

        if (! $this->recursive && $level > 0) {
            return false;
        }

        $current = &$context->getCurrent();
        if (! is_array($current)) {
            return false;
        }

        $iterateContext = new Context($current);

        foreach (array_keys($current) as $key) {
            $iterateContext->setIndex((string)$key);
            $this->recursive($iterateContext, $level + 1);
        }

        $iterateContext->isChanged() && $context->changed();
        return true;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

}
