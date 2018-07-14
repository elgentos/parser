<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 23:17
 */

namespace Dutchlabelshop\Parser\Rule;


use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsTrue;

class Explode extends RuleAbstract
{

    /** @var MatcherInterface */
    private $matcher;
    /** @var string */
    private $delimiter;

    public function __construct(MatcherInterface $matcher = null, string $delimiter = "\n")
    {
        $this->matcher = $matcher ?? new IsTrue;
        $this->delimiter = $delimiter;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function execute(Context $context): bool
    {
        $current = &$context->getCurrent();
        $current = explode($this->delimiter, $current);

        $context->changed();

        return true;
    }
}
