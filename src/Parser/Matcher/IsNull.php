<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 22:53
 */

namespace Dutchlabelshop\Parser\Matcher;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;

class IsNull implements MatcherInterface
{

    /** @var string */
    private $method;

    public function __construct(string $method = 'getCurrent')
    {
        $this->method = $method;
    }

    public function validate(Context $context): bool
    {
        return null === $context->{$this->method}();
    }

}
