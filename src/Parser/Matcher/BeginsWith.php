<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 11:20.
 */

namespace Elgentos\Parser\Matcher;

class BeginsWith extends CoreAbstract
{
    public function execute(&$haystack): bool
    {
        return 0 === \strpos($haystack, $this->needle);
    }
}
