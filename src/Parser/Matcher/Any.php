<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 14:23
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;

class Any implements MatcherInterface
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
            if ($matcher->validate($context)) {
                return true;
            }
        }

        return false;
    }

}
