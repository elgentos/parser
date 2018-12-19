<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:54
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Exceptions\ContextPathNoArrayException;
use Elgentos\Parser\Exceptions\ContextPathNotFoundException;

class Context
{

    /** @var array */
    private $root;
    /** @var string */
    private $index;
    /** @var bool */
    private $changed;

    public function __construct(array &$root = [])
    {
        $this->root = &$root;
        \reset($this->root);
        $this->setIndex((string)\key($this->root));
        $this->changed = false;
    }

    public function &getRoot(): array
    {
        return $this->root;
    }

    public function setIndex(string $index): void
    {
        $this->index = $index;
    }

    public function getIndex(): string
    {
        return $this->index;
    }

    public function exists(): bool
    {
        return isset($this->root[$this->index]);
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
        $path = \explode($seperator, $path);

        $root = &$this->getRoot();
        foreach ($path as $index) {
            if (! isset($root[$index])) {
                throw new ContextPathNotFoundException('Path not found');
            }
            if (! \is_array($root[$index])) {
                throw new ContextPathNoArrayException('Path is not an array');
            }

            $root = &$root[$index];
        }

        return new Context($root);
    }

    public function isChanged(): bool
    {
        return $this->changed;
    }

    public function changed(): void
    {
        $this->changed = true;
    }

}
