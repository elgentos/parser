<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 2-11-18
 * Time: 11:45
 */

namespace Elgentos\Parser\Rule;

class Prepend extends Append
{

    protected function merge(array $array1, array $array2): array
    {
        return array_merge($array2, $array1);
    }

}
