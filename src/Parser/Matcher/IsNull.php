<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 22:53
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;

class IsNull implements MatcherInterface
{

    public function validate(Context $context): bool
    {
        return IsType::factory(IsType::IS_NULL)
                ->validate($context);
    }

}
