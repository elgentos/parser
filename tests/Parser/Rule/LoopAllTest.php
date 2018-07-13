<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 13:13
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\RuleInterface;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use PHPUnit\Framework\TestCase;

class LoopAllTest extends TestCase
{

    public function testConstructorInvalidArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        new LoopAll();
    }

    public function testConstructorOneRule()
    {
        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);
        $this->assertInstanceOf(LoopAll::class, new LoopAll($ruleMock));
    }

    public function testConstructorRules()
    {
        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $this->assertInstanceOf(LoopAll::class, new LoopAll($ruleMock, $ruleMock, $ruleMock, $ruleMock));
    }

    public function testExecuteOnce()
    {
        $root = [];
        $context = new Context($root);

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock2 = new $ruleMock;

        $ruleMock->expects($this->once())
                ->method('parse')
                ->willReturn(true);

        $ruleMock2->expects($this->once())
                ->method('parse')
                ->willReturn(false);

        $loop = new LoopAll($ruleMock, $ruleMock2);
        $this->assertFalse($loop->parse($context));
    }

    public function testExecuteLoop()
    {
        $root = [];
        $context = new Context($root);

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock2 = new $ruleMock;

        $ruleMock->expects($this->exactly(2))
                ->method('parse')
                ->willReturn(true, false);

        $ruleMock2->expects($this->once())
                ->method('parse')
                ->willReturn(true);

        $loop = new LoopAll($ruleMock, $ruleMock2);
        $this->assertFalse($loop->parse($context));
        $this->assertFalse($context->isChanged());
    }

    public function testMatcher()
    {
        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();
        $loop = new LoopAll($ruleMock, $ruleMock);

        $this->assertInstanceOf(IsTrue::class, $loop->getMatcher());
    }

}
