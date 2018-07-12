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

class IsRegExp implements MatcherInterface
{

    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function validate(Context $context): bool
    {
        return preg_match($this->pattern, $context->getIndex()) > 0;
    }

}
