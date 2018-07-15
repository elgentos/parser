<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 2:01
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsTrue;

class Trim extends RuleAbstract
{

    /** @var MatcherInterface */
    private $matcher;
    /** @var string */
    private $charlist;

    public function __construct(MatcherInterface $matcher = null, $charlist = " \t\n\r\0\x0B")
    {
        $this->matcher = $matcher ?? new IsTrue;
        $this->charlist = $charlist;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function execute(Context $context): bool
    {
        $current = &$context->getCurrent();
        $current = trim($current, $this->charlist);
        $context->changed();

        return true;
    }
}
