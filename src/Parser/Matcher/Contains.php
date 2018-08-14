<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 0:21
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;

class Contains implements MatcherInterface
{

    /** @var string */
    private $needle;
    /** @var bool */
    private $caseSensitive;
    /** @var string */
    private $method;

    public function __construct(string $needle, bool $caseSensitive = true, string $method = 'getCurrent')
    {
        $this->needle = $caseSensitive ? $needle : strtolower($needle);
        $this->caseSensitive = $caseSensitive;
        $this->method = $method;
    }


    public function validate(Context $context): bool
    {
        $haystack = $context->{$this->method}();
        if (! $this->caseSensitive) {
            $haystack = strtolower($haystack);
        }

        return false !== strpos($haystack, $this->needle);
    }

}
