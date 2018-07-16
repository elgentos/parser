<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 11:37
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use Elgentos\Parser\Matcher\IsTrue;

class Yaml extends RuleAbstract
{

    /** @var MatcherInterface */
    private $matcher;

    public function __construct(MatcherInterface $matcher = null)
    {
        $this->matcher = $matcher ?? new IsTrue;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function execute(Context $context): bool
    {
        $yaml = new \Symfony\Component\Yaml\Yaml();

        $yamlContent = $context->getCurrent();
        $current = &$context->getCurrent();

        $current = $yaml->parse($yamlContent);
        $context->changed();

        return true;
    }

}
