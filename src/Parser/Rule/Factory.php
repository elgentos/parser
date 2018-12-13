<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-12-18
 * Time: 12:09
 */

namespace Elgentos\Parser\Rule;


use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;

class Factory implements RuleInterface
{

    /** @var \ReflectionClass */
    private $className;

    /**
     * Factory constructor.
     *
     * @param string $className\
     * @throws \ReflectionException
     */
    public function __construct(string $className)
    {
        $this->className = new \ReflectionClass($className);
    }

    public function parse(Context $context): bool
    {
        $current = &$context->getCurrent();

        $class = $this->className;
        $current = $class->newInstanceArgs($current);

        return true;
    }

}