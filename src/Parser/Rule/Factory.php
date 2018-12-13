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
    /** @var array */
    private $arguments;
    /** @var array*/
    private $setters;
    /** @var bool */
    private $defaults;

    /**
     * Factory constructor.
     *
     * @param string $className
     * @param array $arguments
     * @param array $setters
     * @throws \ReflectionException
     */
    public function __construct(string $className, array $arguments = null, array $setters = null)
    {
        $this->className = new \ReflectionClass($className);

        if (null !== $arguments) {
            $numArguments = count($arguments);
            $hasDefaults = range(0, $numArguments - 1) !== array_keys($arguments);
            $this->arguments = $hasDefaults ? array_keys($arguments) : $arguments;
            $this->defaults = $hasDefaults ? $arguments : array_fill(0, $numArguments, null);
        }

        $this->setters = $setters;
    }

    public function parse(Context $context): bool
    {
        $current = &$context->getCurrent();

        if (! is_array($current)) {
            return false;
        }

        $arguments = $this->getArguments($current);
        $setters = $this->getSetters($current);

        $class = $this->className;

        $object = $class->newInstanceArgs($arguments);

        $this->applySetters($object, $setters);

        $current = $object;

        return true;
    }

    private function getArguments(array $current): array
    {
        if (null === $this->arguments) {
            return $current;
        }

        return array_map(function($fieldName, $default) use ($current) {
            return $current[$fieldName] ?? $default;
        }, $this->arguments, $this->defaults);
    }

    private function getSetters(array $current): array
    {
        if (null === $this->setters) {
            return [];
        }

        return array_map(function($setter) use ($current) {
            return $current[$setter] ?? null;
        }, array_keys($this->setters));
    }

    private function applySetters($object, array $setters)//: void
    {
        if (count($setters) < 1) {
            return;
        }

        array_map(function($data, $setter) use ($object) {
            if (null === $data) {
                return;
            }
            $object->{$setter}($data);
        }, $setters, $this->setters);
    }

}