<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:54
 */

namespace Dutchlabelshop\Parser;

use Dutchlabelshop\Parser\Exceptions\ContextPathNoArrayException;
use Dutchlabelshop\Parser\Exceptions\ContextPathNotFoundException;

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

    public function search(string $path, string $seperator = '/'): Context
    {
        $path = explode($seperator, $path);

        $root = &$this->getRoot();
        foreach ($path as $index) {
            if (! isset($root[$index])) {
                throw new ContextPathNotFoundException('Path not found');
            }
            if (! is_array($root[$index])) {
                throw new ContextPathNoArrayException('Path is not an array');
            }

            $root = &$root[$index];
        }

        return new Context($root);
    }

}
