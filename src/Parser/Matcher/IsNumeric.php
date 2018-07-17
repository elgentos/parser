<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-7-18
 * Time: 14:28
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;

class IsNumeric implements MatcherInterface
{

    public function validate(Context $context): bool
    {
        return IsType::factory(IsType::IS_NUMERIC)
                ->validate($context);
    }

}
