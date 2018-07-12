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

class IsTrue implements MatcherInterface
{

    public function validate(Context $context): bool
    {
        return true;
    }

}

