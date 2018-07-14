<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 0:58
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsTrue;

class Callback extends RuleAbstract
{

    /** @var \Closure */
    private $callback;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(\Closure $callback, MatcherInterface $matcher = null)
    {
        $this->callback = $callback;
        $this->matcher = $matcher ?? new IsTrue;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function execute(Context $context): bool
    {
        $callback = $this->callback;
        return $callback($context);
    }

}
