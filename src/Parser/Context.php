<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:54
 */

namespace Dutchlabelshop\Parser;

class Context
{

    /** @var array */
    private $root;
    /** @var string */
    private $index;

    public function __construct(array &$root = [])
    {
        $this->root = &$root;
        reset($this->root);
        $this->setIndex((string)key($this->root));
    }

    public function &getRoot(): array
    {
        return $this->root;
    }

    public function setIndex(string $index)//: void
    {
        $this->index = $index;
    }

    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     *
     * @return mixed
     */
    public function &getCurrent()
    {
        return $this->root[$this->index];
    }

}
