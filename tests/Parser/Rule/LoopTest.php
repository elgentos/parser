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

class LoopTest extends TestCase
{

    public function testConstructorInvalidArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Loop();
    }

    public function testConstructorOneRule()
    {
        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock->expects($this->exactly(0))
                ->method('addRule');

        $this->expectException(\InvalidArgumentException::class);
        new Loop($ruleMock);
    }

    public function testConstructorTwoRules()
    {
        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock->expects($this->exactly(2))
                ->method('addRule');

        new Loop($ruleMock, $ruleMock);
    }

    public function testConstructorTenRules()
    {
        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock->expects($this->exactly(10))
                ->method('addRule');

        new Loop($ruleMock, $ruleMock, $ruleMock, $ruleMock, $ruleMock, $ruleMock, $ruleMock, $ruleMock, $ruleMock, $ruleMock);
    }

    public function testExecuteFirstRule()
    {
        $root = [];
        $context = new Context($root);

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock2 = new $ruleMock;

        $ruleMock->expects($this->once())
                ->method('parse')
                ->willReturn(true);

        $ruleMock2->expects($this->exactly(0))
                ->method('parse');

        $loop = new Loop($ruleMock, $ruleMock2);

        $this->assertTrue($loop->parse($context));
    }

    public function testMatcher()
    {
        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();
        $loop = new Loop($ruleMock, $ruleMock);

        $this->assertInstanceOf(IsTrue::class, $loop->getMatcher());
    }

}
