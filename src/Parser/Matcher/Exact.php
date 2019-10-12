<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 11:25
 */

namespace Elgentos\Parser\Matcher;

class Exact extends CoreAbstract
{
    public function execute(&$haystack): bool
    {
        return $haystack === $this->needle;
    }
}
