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
use PHPUnit\Framework\TestCase;

class LoopAnyTest extends TestCase
{

    public function testConstructorInvalidArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        new LoopAny();
    }

    public function testConstructorOneRule()
    {
        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);
        new LoopAny($ruleMock);
    }

    public function testConstructorRules()
    {
        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $this->assertInstanceOf(LoopAny::class, new LoopAny($ruleMock, $ruleMock, $ruleMock, $ruleMock));
    }

    public function testExecuteOnce()
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
                ->willReturn(false);

        $loop = new LoopAny($ruleMock, $ruleMock2);
        $this->assertTrue($loop->match($context));
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
                ->willReturn(false, false);

        $ruleMock2->expects($this->exactly(2))
                ->method('parse')
                ->willReturn(true, false);

        $loop = new LoopAny($ruleMock, $ruleMock2);
        $this->assertFalse($loop->parse($context));
        $this->assertFalse($context->isChanged());
    }

}
