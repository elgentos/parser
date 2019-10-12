<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-7-18
 * Time: 9:01.
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;

class Import extends FileAbstract
{
    /** @var string */
    private $rootDir;

    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function parse(Context $context): bool
    {
        $filename = $context->getCurrent();
        if (!\is_string($filename)) {
            throw new RuleInvalidContextException(sprintf('%s expects a path/to/file', self::class));
        }

        $current = &$context->getCurrent();
        $current = $this->getContent($filename);

        $context->changed();

        return true;
    }

    /**
     * Get file contents.
     *
     * @param string $filename
     *
     * @return string
     */
    protected function getContent(string $filename): string
    {
        return \file_get_contents($this->getSafepath($this->rootDir.DIRECTORY_SEPARATOR.$filename));
    }
}
