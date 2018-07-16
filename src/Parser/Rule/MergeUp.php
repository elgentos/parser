<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 0:13
 */

namespace Elgentos\Parser\Rule;

class MergeUp extends MergeDown
{

    /**
     * Recursive nice merge
     *
     * @param array $source
     * @param array $destination
     * @return array
     */
    protected function merge(array &$source, array &$destination): array
    {
        // Flip direction
        return parent::merge($destination, $source);
    }

}
