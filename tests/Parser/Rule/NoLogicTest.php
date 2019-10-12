<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 1:37.
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class NoLogicTest extends TestCase
{
    public function testLogicalFalse()
    {
        $root = [];
        $context = new Context($root);

        $rule = new NoLogic(false);
        $this->assertFalse($rule->parse($context));
        $this->assertFalse($context->isChanged());
    }

    public function testLogicalTrue()
    {
        $root = [];
        $context = new Context($root);

        $rule = new NoLogic(true);
        $this->assertTrue($rule->parse($context));
        $this->assertFalse($context->isChanged());
    }
}
