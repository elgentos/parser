<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-7-18
 * Time: 9:01
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use Elgentos\Parser\Matcher\IsTrue;

class Import extends FileAbstract
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
        $filename = $context->getCurrent();

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
        return file_get_contents($this->getSafepath($this->rootDir . DIRECTORY_SEPARATOR . $filename));
    }

}
