<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-7-18
 * Time: 13:26.
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;

class IsType implements MatcherInterface
{
    const IS_BOOL = 'bool';
    const IS_STRING = 'string';
    const IS_ARRAY = 'array';
    const IS_INT = 'int';
    const IS_OBJECT = 'object';
    const IS_FLOAT = 'float';
    const IS_NUMERIC = 'numeric';
    const IS_NULL = 'null';

    /** @var string */
    private $type;
    /** @var string */
    private $method;

    /** @var array */
    private $validators = [
            self::IS_BOOL       => 'is_bool',
            self::IS_STRING     => 'is_string',
            self::IS_ARRAY      => 'is_array',
            self::IS_INT        => 'is_int',
            self::IS_OBJECT     => 'is_object',
            self::IS_FLOAT      => 'is_float',
            self::IS_NUMERIC    => 'is_numeric',
            self::IS_NULL       => 'is_null',
    ];

    public function __construct(string $type, string $method = 'getCurrent')
    {
        if (!isset($this->validators[$type])) {
            throw new \InvalidArgumentException(
                    sprintf('"%s" is not a allowed type for this class', $type)
            );
        }

        $this->type = $type;
        $this->method = $method;
    }

    public function validate(Context $context): bool
    {
        $validator = $this->validators[$this->type];

        return $validator($context->{$this->method}());
    }

    /**
     * Allow static creation.
     *
     * @param string $type
     * @param string $method
     *
     * @return IsType
     */
    public static function factory(string $type, string $method = 'getCurrent'): self
    {
        return new self($type, $method);
    }
}
