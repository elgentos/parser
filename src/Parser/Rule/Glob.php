<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-7-18
 * Time: 11:44
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;

class Glob extends FileAbstract
{

    /** @var string */
    private $rootDir;

    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function parse(Context $context): bool
    {
        $path = $context->getCurrent();
        if (! \is_string($path)) {
            throw new RuleInvalidContextException(sprintf("%s expects a string", self::class));
        }

        $files = $this->getFiles($path);

        $current = &$context->getCurrent();
        $current = $files;

        $context->changed();

        return true;
    }

    protected function getFiles($path): array
    {
        $safepath = $this->getSafepath($this->rootDir . DIRECTORY_SEPARATOR . $path);

        $fileIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                        $safepath,
                        \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS
                )
        );

        $files = [];

        $fileIterator->rewind();
        while ($fileIterator->valid()) {
            $files[] = $path . DIRECTORY_SEPARATOR . $fileIterator->getSubPathName();
            $fileIterator->next();
        }

        \sort($files, SORT_STRING | SORT_NATURAL);

        return $files;
    }

}
