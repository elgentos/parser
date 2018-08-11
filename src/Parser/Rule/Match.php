<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-8-18
 * Time: 20:50
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use Elgentos\Parser\Interfaces\RuleInterface;

class Match implements RuleInterface
{

    /** @var MatcherInterface */
    private $matcher;

    /**
     * Match constructor.
     */
    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    public function getMatcher()
    {
        return $this->matcher;
    }

    public function parse(Context $context): bool
    {
        return $this->matcher->validate($context);
    }

}
