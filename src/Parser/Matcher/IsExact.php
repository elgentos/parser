<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 11:25
 */

namespace Dutchlabelshop\Parser\Matcher;


use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;

class IsExact implements MatcherInterface
{

    /** @var mixed */
    private $matcher;
    /** @var string */
    private $method;

    public function __construct($matcher, string $method = 'getIndex')
    {
        $this->matcher = $matcher;
        $this->method = $method;
    }

    public function validate(Context $context): bool
    {
        return $this->matcher === $context->{$this->method}();
    }

}

