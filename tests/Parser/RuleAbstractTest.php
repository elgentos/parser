<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 9:53
 */

namespace Dutchlabelshop\Parser;

use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Interfaces\RuleInterface;
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

    public function testExecuteRule()
    {
        $rule = $this->ruleAbstract;
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $this->assertFalse($rule->executeRule($context));

        $ruleMock->expects($this->once())
                ->method('parse')
                ->willReturn(true);

        $rule->addRule($ruleMock);
        $this->assertTrue($rule->executeRule($context));
    }

    public function testAddRule()
    {
        $rule = $this->ruleAbstract;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock->expects($this->exactly(0))
                ->method('parse')
                ->willReturn(true);

        $rule->addRule($ruleMock);
    }

    public function testMatch()
    {
        $context = $this->context;
        $rule = $this->getMockBuilder(get_class($this->ruleAbstract))
                ->setMethods(['getMatcher'])
                ->getMock();

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

}
