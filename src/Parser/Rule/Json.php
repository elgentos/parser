<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:25
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use Elgentos\Parser\Matcher\IsTrue;

class Json extends RuleAbstract
{
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(MatcherInterface $matcher = null)
    {
        $this->matcher = $matcher ?? new IsTrue;
    }

    public function execute(Context $context): bool
    {
        $jsonData = $context->getCurrent();

        $current = &$context->getCurrent();
        $current = json_decode($jsonData, true);
        $context->changed();

        return true;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

}
