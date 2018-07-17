<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-7-18
 * Time: 11:44
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use Elgentos\Parser\Matcher\IsTrue;

class Glob extends FileAbstract
{

    /** @var string */
    private $rootDir;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(string $rootDir, MatcherInterface $matcher = null)
    {
        $this->rootDir = $rootDir;
        $this->matcher = $matcher ?? new IsTrue;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function execute(Context $context): bool
    {
        $path = $context->getCurrent();

        $files = $this->getFiles($path);

        $current = &$context->getCurrent();
        $current = $files;

        $context->changed();

        return true;
    }

    protected function getFiles($path): array
    {
        $path = $this->getSafepath($this->rootDir . DIRECTORY_SEPARATOR . $path);

        $fileIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                        $path,
                        \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS
                )
        );

        $files = [];

        $fileIterator->rewind();
        while ($fileIterator->valid()) {
            $files[] = $fileIterator->getSubPathName();
            $fileIterator->next();
        }

        sort($files, SORT_STRING | SORT_NATURAL);

        return $files;
    }

}
