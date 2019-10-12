<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 23:17.
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Interfaces\RuleInterface;

class Explode implements RuleInterface
{
    /** @var string */
    private $delimiter;

    public function __construct(string $delimiter = "\n")
    {
        $this->delimiter = $delimiter;
    }

    public function parse(Context $context): bool
    {
        $current = &$context->getCurrent();
        if (!\is_string($current)) {
            throw new RuleInvalidContextException(sprintf('%s expects a string', self::class));
        }

        $current = \explode($this->delimiter, $current);

        $context->changed();

        return true;
    }
}
