<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 11:20
 */

namespace Elgentos\Parser\Matcher;

class EndsWith extends CoreAbstract
{
    public function execute(&$haystack): bool
    {
        return \substr($haystack, - \strlen($this->needle)) === $this->needle;
    }
}
