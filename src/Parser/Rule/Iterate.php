<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:25
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Interfaces\RuleInterface;

class Iterate implements RuleInterface
{
    /** @var RuleInterface */
    private $rule;
    /** @var bool */
    private $recursive;

    public function __construct(RuleInterface $rule, bool $recursive)
    {
        $this->rule = $rule;
        $this->recursive = $recursive;
    }

    public function parse(Context $context): bool
    {
        return $this->recursive($context);
    }

    private function recursive(Context $context, $level = 0): bool
    {
        if (! $this->recursive && $level > 0) {
            return false;
        }

        $current = &$context->getCurrent();
        if (! \is_array($current)) {
            throw new RuleInvalidContextException(sprintf("%s expects a array", self::class));
        }

        $iterateContext = new Context($current);
        foreach (\array_keys($current) as $key) {
            $iterateContext->setIndex((string)$key);

            if ($this->rule->parse($iterateContext)) {
                continue;
            }
            if (! \is_array($iterateContext->getCurrent())) {
                continue;
            }

            $this->recursive($iterateContext, $level + 1);
        }

        $iterateContext->isChanged() && $context->changed();
        return true;
    }
}
