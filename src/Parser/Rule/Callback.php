<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 0:58
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;

class Callback implements RuleInterface
{

    /** @var \Closure */
    private $callback;

    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    public function parse(Context $context): bool
    {
        $callback = $this->callback;
        return !! $callback($context);
    }
}
