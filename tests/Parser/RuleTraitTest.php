<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 9:53
 */

namespace Dutchlabelshop\Parser;

use Dutchlabelshop\Parser\Interfaces\RuleInterface;
use PHPUnit\Framework\TestCase;

class RuleTraitTest extends TestCase
{

    /** @var RuleTrait */
    private $ruleTrait;
    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [];
        $this->context = new Context($root);
        $this->ruleTrait = $this->getMockForTrait(RuleTrait::class);
    }

    public function testExecuteRule()
    {
        $rule = $this->ruleTrait;
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
        $rule = $this->ruleTrait;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock->expects($this->exactly(0))
                ->method('parse')
                ->willReturn(true);

        $rule->addRule($ruleMock);
    }
}
