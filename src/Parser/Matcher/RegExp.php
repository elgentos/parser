<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 11:25
 */

namespace Elgentos\Parser\Matcher;


use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;

class RegExp implements MatcherInterface
{

    /** @var string */
    private $pattern;
    /** @var string */
    private $method;

    public function __construct(string $pattern, string $method = 'getCurrent')
    {
        $this->pattern = $pattern;
        $this->method = $method;
    }

    public function validate(Context $context): bool
    {
        return preg_match($this->pattern, $context->{$this->method}()) > 0;
    }

}

