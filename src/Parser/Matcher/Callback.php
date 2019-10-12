<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 11:25.
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;

class Callback implements MatcherInterface
{
    /** @var \Closure */
    private $callback;

    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    public function validate(Context $context): bool
    {
        $callback = $this->callback;

        return (bool) $callback($context);
    }
}
