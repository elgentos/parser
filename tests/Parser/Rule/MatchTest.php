<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-8-18
 * Time: 20:49.
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use Elgentos\Parser\Interfaces\RuleInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    /** @var MockObject */
    private $ruleMock;
    /** @var MockObject */
    private $matchMock;

    public function setUp()
    {
        $this->matchMock = $this->getMockBuilder(MatcherInterface::class)
                ->getMock();
        $this->ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();
    }

    public function testGetMatcher()
    {
        $matchMock = $this->matchMock;
        $match = new Match($matchMock);

        $this->assertSame($matchMock, $match->getMatcher());
    }

    public function testImplementsRuleInterface()
    {
        $matchMock = $this->matchMock;
        $match = new Match($matchMock);

        $this->assertInstanceOf(RuleInterface::class, $match);
    }

    public function testParse()
    {
        $matchMock = $this->matchMock;
        $match = new Match($matchMock);

        $matchMock->expects($this->exactly(2))
                ->method('validate')
                ->willReturn(true, false);

        $data = [];
        $context = new Context($data);

        $this->assertTrue($match->parse($context));
        $this->assertFalse($match->parse($context));
    }

    public function testNextRule()
    {
        $matchMock = $this->matchMock;
        $ruleMock = $this->ruleMock;

        $match = new Match($matchMock, $ruleMock);

        $matchMock->expects($this->exactly(2))
                ->method('validate')
                ->willReturn(true);

        $ruleMock->expects($this->exactly(2))
                ->method('parse')
                ->willReturn(true, false);

        $data = [];
        $context = new Context($data);

        $this->assertTrue($match->parse($context));
        $this->assertFalse($match->parse($context));
    }
}
