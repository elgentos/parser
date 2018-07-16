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
        $this->matcher = $matcher ?? new IsTrue;
    }

    public function execute(Context $context): bool
    {
        $root = &$context->getRoot();

        $iterateContext = new Context($root);
        foreach ($root as $key => &$value) {
            $iterateContext->setIndex((string)$key);

            if ($this->rule->parse($iterateContext)) {
                continue;
            }

            if ($this->recursive && is_array($value)) {
                $resursiveContext = new Context($value);
                $this->parse($resursiveContext);

                // Pass changed up
                $resursiveContext->isChanged() && $iterateContext->changed();
            }
        }

        $iterateContext->isChanged() && $context->changed();
        return true;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

}
