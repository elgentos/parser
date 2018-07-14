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
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\RuleAbstract;

class Explode extends RuleAbstract
{

    /** @var MatcherInterface */
    private $matcher;
    /** @var string */
    private $delimiter;

    public function __construct(MatcherInterface $matcher = null, string $delimiter = "\n")
    {
        $this->matcher = $matcher ?? new IsExact('__explode');
        $this->delimiter = $delimiter;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function parse(Context $context): bool
    {
        if (! $this->match($context)) {
            return false;
        }

        $current = &$context->getCurrent();
        $current = explode($this->delimiter, $current);

        $context->changed();

        return true;
    }
}
