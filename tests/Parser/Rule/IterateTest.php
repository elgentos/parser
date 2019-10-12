<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 10:08.
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Interfaces\RuleInterface;
use PHPUnit\Framework\TestCase;

class IterateTest extends TestCase
{
    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [
                'root' => [],
        ];
        $this->context = new Context($root);
    }

    public function testParse()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $rule = new Iterate($ruleMock, false);

        $current = &$context->getCurrent();
        $current = array_fill(0, 10, 'value');

        $ruleMock->expects($this->exactly(10))
                ->method('parse')
                ->willReturn(false);

        $this->assertTrue($rule->parse($context));
    }

    public function testParseRule()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $rule = new Iterate($ruleMock, false);

        $current = &$context->getCurrent();
        $current = array_fill(0, 10, 'value');

        $ruleMock->expects($this->exactly(10))
                ->method('parse')
                ->willReturn(true);

        $this->assertTrue($rule->parse($context));
    }

    public function testNoRecursive()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $rule = new Iterate($ruleMock, false);

        $current = &$context->getCurrent();
        $repeat = array_fill(0, 10, 'deep');
        $current = array_fill(0, 10, $repeat);

        $ruleMock->expects($this->exactly(10))
                ->method('parse')
                ->willReturn(false);

        $this->assertTrue($rule->parse($context));
        $this->assertFalse($context->isChanged());
    }

    public function testRecursive()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $rule = new Iterate($ruleMock, true);

        $current = &$context->getCurrent();
        $repeat = array_fill(0, 10, 'deep');
        $current = array_fill(0, 10, $repeat);

        $ruleMock->expects($this->exactly(110))
                ->method('parse')
                ->willReturn(false);

        $this->assertTrue($rule->parse($context));
        $this->assertFalse($context->isChanged());
    }

    public function testRecursiveShouldSetChanged()
    {
        $context = $this->context;

        $subRule = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $subRule->expects($this->exactly(2))
                ->method('parse')
                ->willReturnCallback(function (Context $context) {
                    if ('two' === $context->getIndex()) {
                        $context->changed();
                    }

                    return false;
                });

        /** @var Iterate $rule */
        $rule = new Iterate($subRule, true);

        $current = &$context->getCurrent();
        $current['one'] = [
            'two' => 'test',
        ];

        $this->assertTrue($rule->parse($context));
        $this->assertTrue($context->isChanged());
    }

    public function testInvalidType()
    {
        $subRule = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $rule = new Iterate($subRule, false);

        $root = ['test'];
        $context = new Context($root);

        $this->expectException(RuleInvalidContextException::class);
        $rule->parse($context);
    }
}
