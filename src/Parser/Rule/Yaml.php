<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 11:37
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;

class Yaml implements RuleInterface
{

    public function parse(Context $context): bool
    {
        $yaml = new \Symfony\Component\Yaml\Yaml();

        $yamlContent = $context->getCurrent();
        $current = &$context->getCurrent();

        $current = $yaml->parse($yamlContent);
        $context->changed();

        return true;
    }

}
