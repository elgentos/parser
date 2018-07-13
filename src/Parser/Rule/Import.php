<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-7-18
 * Time: 9:01
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\RuleAbstract;

class Import extends RuleAbstract
{

    /** @var string */
    private $rootDir;
    /** @var string */
    private $newKey;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(string $rootDir, string $newKey, MatcherInterface $matcher = null)
    {
        $this->rootDir = $this->safePath($rootDir);
        $this->newKey = $newKey;
        $this->matcher = $matcher ?? new IsExact('__import');
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function parse(Context $context): bool
    {
        if (! $this->match($context)) {
            return false;
        }

        $filename = $context->getCurrent();
        $root = &$context->getRoot();
        unset($root[$context->getIndex()]);

        $context->setIndex($this->newKey);
        $current = &$context->getCurrent();
        $current = $this->getContent($filename);

        $context->changed();

        return true;
    }

    /**
     * Get file contents
     *
     * @param string $filename
     * @return string
     */
    protected function getContent(string $filename): string
    {
        return file_get_contents($this->getFilepath($filename));
    }

    /**
     * Filter nasty strings from path
     *
     * @param string $path
     * @return string
     */
    private function safePath(string $path): string
    {
        while (($newPath = str_replace(['..', '//'], ['', '/'], $path)) !== $path) {
            $path = $newPath;
        }

        return str_replace(['..', '//'], ['', '/'], $path);
    }

    /**
     * Get file path
     *
     * @param string $filename
     * @return string
     */
    private function getFilepath(string $filename): string
    {
        return $this->rootDir . '/' . $this->safePath($filename);
    }

}
