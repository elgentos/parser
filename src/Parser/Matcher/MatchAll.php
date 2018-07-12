<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 14:23
 */

namespace Dutchlabelshop\Parser\Matcher;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;

class MatchAll implements MatcherInterface
{
    /** @var []MatcherInterface */
    private $matchers;

    public function __construct(MatcherInterface ...$matchers)
    {
        if (func_num_args() < 1) {
            throw new \InvalidArgumentException('Provide at least one matcher');
        }

        $this->matchers = $matchers;
    }

    public function validate(Context $context): bool
    {
        foreach ($this->matchers as $matcher) {
            if (! $matcher->validate($context)) {
                return false;
            }
        }

        return true;
    }

}
