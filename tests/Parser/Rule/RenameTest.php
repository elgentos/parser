<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 21:27
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class RenameTest extends TestCase
{
    public function testParse()
    {
        $root = ['test' => 'text'];
        $context = new Context($root);

        $rule = new Rename('test2');

        $this->assertTrue($rule->parse($context));
        $this->assertSame(['test2' => 'text'], $context->getRoot());
        $this->assertTrue($context->isChanged());
        $this->assertSame('test2', $context->getIndex());
    }
}
