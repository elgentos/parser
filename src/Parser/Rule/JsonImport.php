<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:25
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\RuleAbstract;

class JsonImport extends RuleAbstract
{
    /** @var string */
    private $rootDir;
    /** @var bool */
    private $mergeRecursive;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(string $rootDir, bool $mergeRecursive = false, MatcherInterface $matcher = null)
    {
        $this->rootDir = $this->safePath($rootDir);
        $this->mergeRecursive = $mergeRecursive;
        $this->matcher = $matcher ?? new IsExact('__import');
    }

    public function parse(Context $context): bool
    {
        if (! $this->match($context)) {
            return false;
        }

        $root = &$context->getRoot();
        $filename = $context->getCurrent();
        unset($root[$context->getIndex()]);

        $content = $this->getContent($filename);
        $root = $this->niceMerge($content, $root);

        $context = new Context($root);
        return $this->executeRule($context);
    }

    /**
     * Recursive nice merge
     *
     * @param array $result
     * @param array $new
     * @return array
     */
    protected function niceMerge(array $result, array $new): array
    {
        foreach ($new as $key => &$value) {
            if (
                    ! isset($result[$key]) ||
                    !is_array($value) ||
                    ! $this->mergeRecursive
            ) {
                $result[$key] = $value;
                continue;
            }

            $result[$key] = $this->niceMerge($result[$key], $value);
        }

        return $result;
    }

    /**
     * Get file contents
     *
     * @param string $filename
     * @return array
     */
    protected function getContent(string $filename): array
    {
        return json_decode(file_get_contents($this->getFilepath($filename)), true);
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

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }
}
