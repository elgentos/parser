<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 2-11-18
 * Time: 11:45
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;

class Append implements RuleInterface
{
    public function parse(Context $context): bool
    {
        $root = &$context->getRoot();
        $index = $context->getIndex();
        $current = $context->getCurrent();

        unset($root[$index]);

        $root = $this->merge($root, $current);

        return true;
    }

    protected function merge(array $array1, array $array2): array
    {
        return array_merge($array1, $array2);
    }
}
