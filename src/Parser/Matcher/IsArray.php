<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 14:44
 */

namespace Dutchlabelshop\Parser\Matcher;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;

class IsArray implements MatcherInterface
{

    /** @var string */
    private $method;

    public function __construct(string $method = 'getCurrent')
    {
        $this->method = $method;
    }

    public function validate(Context $context): bool
    {
        return is_array($context->{$this->method}());
    }

}
