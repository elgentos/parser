<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 0:21
 */

namespace Elgentos\Parser\Matcher;

class Contains extends CoreAbstract
{
    public function execute(&$haystack): bool
    {
        return false !== \strpos($haystack, $this->needle);
    }
}
