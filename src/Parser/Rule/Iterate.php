<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-7-18
 * Time: 14:25
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\RuleInterface;
use Dutchlabelshop\Parser\RuleTrait;

class Iterate implements RuleInterface
{
    use RuleTrait;

    /** @var bool */
    private $recursive;

    public function __construct(bool $recursive = false)
    {
        $this->recursive = $recursive;
    }

    public function match(Context $context): bool
    {
        return true;
    }

    public function parse(Context $context): bool
    {
        $root = &$context->getRoot();
        foreach ($root as $key => &$value) {
            $context->setIndex((string)$key);

            if ($this->recursive && is_array($value)) {
                $this->parse(new Context($value));
                continue;
            }

            $this->executeRule($context);
        }

        return true;
    }

}
