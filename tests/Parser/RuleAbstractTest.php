<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 9:53
 */

namespace Dutchlabelshop\Parser;

use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Rule\RuleAbstract;
use PHPUnit\Framework\TestCase;

class RuleAbstractTest extends TestCase
{

    /** @var RuleAbstract */
    private $ruleAbstract;
    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [];
        $this->context = new Context($root);
        $this->ruleAbstract = $this->getMockForAbstractClass(RuleAbstract::class);
    }

    public function testMatch()
    {
        $context = $this->context;
        $rule = $this->ruleAbstract;

        $matcherMock = $this->getMockBuilder(MatcherInterface::class)
                ->getMock();

        $matcherMock->method('validate')
                ->willReturn(true, false);

        $rule->expects($this->exactly(2))
                ->method('getMatcher')
                ->willReturn($matcherMock);

        /** @var RuleAbstract $rule */
        $this->assertTrue($rule->match($context));
        $this->assertFalse($rule->match($context));
    }

    public function testParse()
    {
        $context = $this->context;
        $rule = $this->ruleAbstract;

        $matcherMock = $this->getMockBuilder(MatcherInterface::class)
                ->getMock();

        $matcherMock->method('validate')
                ->willReturn(false, true);

        $rule->expects($this->exactly(2))
                ->method('getMatcher')
                ->willReturn($matcherMock);

        $rule->expects($this->once())
                ->method('execute')
                ->willReturn(true);

        /** @var RuleAbstract $rule */
        $this->assertFalse($rule->parse($context));
        $this->assertTrue($rule->parse($context));
    }

}
