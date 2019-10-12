<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 11:25
 */

namespace Elgentos\Parser\Matcher;

class RegExp extends CoreAbstract
{
    public function execute(&$haystack): bool
    {
        return 0 < \preg_match($this->needle, $haystack);
    }
}
