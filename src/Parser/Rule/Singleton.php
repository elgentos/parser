<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-12-18
 * Time: 23:57
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;

class Singleton implements RuleInterface
{

    /** @var Factory */
    private $factory;
    /** @var mixed */
    private $object;

    /**
     * Singleton constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritdoc
     */
    public function parse(Context $context): bool
    {
        $current = &$context->getCurrent();

        if (null !== $this->object) {
            $current = $this->object;
            return true;
        }

        if (! $this->factory->parse($context)) {
            return false;
        }

        $this->object = $current;
        return true;
    }

}