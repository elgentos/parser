<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-8-18
 * Time: 20:49
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

    public function setUp()
    {
        $this->ruleMock = $this->getMockBuilder(MatcherInterface::class)
                ->getMock();
    }

    public function testGetMatcher()
    {
        $ruleMock = $this->ruleMock;
        $match = new Match($ruleMock);

        $this->assertSame($ruleMock, $match->getMatcher());
    }

    public function testImplementsRuleInterface()
    {
        $ruleMock = $this->ruleMock;
        $match = new Match($ruleMock);

        $this->assertInstanceOf(RuleInterface::class, $match);
    }

    public function testParse()
    {
        $ruleMock = $this->ruleMock;
        $match = new Match($ruleMock);

        $ruleMock->expects($this->exactly(2))
                ->method('validate')
                ->willReturn(true, false);

        $data = [];
        $context = new Context($data);

        $this->assertTrue($match->parse($context));
        $this->assertFalse($match->parse($context));
    }

}
