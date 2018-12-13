<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-12-18
 * Time: 21:26
 */

namespace Elgentos\Parser\Rule;


use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;

class ContextIndex implements RuleInterface
{
    /** @var string */
    private $newIndex;

    /**
     * ContextIndex constructor.
     *
     * @param string $newIndex
     */
    public function __construct(string $newIndex)
    {
        $this->newIndex = $newIndex;
    }

    public function parse(Context $context): bool
    {
        $context->setIndex($this->newIndex);

        return true;
    }

}