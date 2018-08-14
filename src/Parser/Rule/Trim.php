<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 2:01
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Interfaces\RuleInterface;

class Trim implements RuleInterface
{

    const DEFAULT_CHARLIST = " \t\n\r\0\x0B";

    /** @var string */
    private $charlist;

    public function __construct(string $charlist = self::DEFAULT_CHARLIST)
    {
        $this->charlist = $charlist;
    }

    public function parse(Context $context): bool
    {
        $current = &$context->getCurrent();
        if (! \is_string($current)) {
            throw new RuleInvalidContextException(sprintf("%s expects a string", self::class));
        }

        $current = \trim($current, $this->charlist);
        $context->changed();

        return true;
    }

}
