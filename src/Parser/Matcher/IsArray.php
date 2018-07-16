<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 14:44
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;

class IsArray implements MatcherInterface
{

    public function validate(Context $context): bool
    {
        return is_array($context->getCurrent());
    }

}
