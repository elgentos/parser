<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 21:23
 */

namespace Elgentos\Parser\Rule;


use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;

class Rename implements RuleInterface
{

    /** @var string */
    private $newIndex;

    public function __construct(string $newIndex)
    {
        $this->newIndex = $newIndex;
    }

    public function parse(Context $context): bool
    {
        $root = &$context->getRoot();

        $current = $context->getCurrent();
        unset($root[$context->getIndex()]);
        $root[$this->newIndex] = $current;

        $context->setIndex($this->newIndex);
        $context->changed();

        return true;
    }

}
