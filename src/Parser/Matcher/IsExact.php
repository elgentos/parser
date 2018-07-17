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

class IsExact implements MatcherInterface
{

    /** @var mixed */
    private $matcher;
    /** @var string */
    private $method;

    public function __construct($matcher, string $method = 'getCurrent')
    {
        $this->matcher = $matcher;
        $this->method = $method;
    }

    public function validate(Context $context): bool
    {
        return $this->matcher === $context->{$this->method}();
    }

}

