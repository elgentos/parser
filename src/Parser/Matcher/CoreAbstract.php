<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 11:28
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;

abstract class CoreAbstract implements MatcherInterface
{

    /** @var mixed */
    protected $needle;
    /** @var string */
    protected $method;
    /** @var bool */
    protected $caseSensitive;

    public function __construct($needle, string $method = 'getCurrent', bool $caseSensitive = true)
    {
        $caseSensitive = ! \is_string($needle) ? true : $caseSensitive;
        $this->caseSensitive = $caseSensitive;

        $this->needle = $caseSensitive ? $needle : \strtolower($needle);
        $this->method = $method;
    }

    public function validate(Context $context): bool
    {
        $haystack = $context->{$this->method}();
        if (! $this->caseSensitive) {
            $haystack = \strtolower($haystack);
        }

        return $this->execute($haystack);
    }

    abstract public function execute(&$haystack): bool;
}
