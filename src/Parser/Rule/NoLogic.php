<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 1:34
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;

class NoLogic implements RuleInterface
{

    /** @var bool */
    private $return;

    public function __construct(bool $return)
    {
        $this->return = $return;
    }

    public function match(Context $context): bool
    {
        return $this->return;
    }

    public function parse(Context $context): bool
    {
        return $this->return;
    }

}
