<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 10:08
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\RuleInterface;
use PHPUnit\Framework\TestCase;

class IterateTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [];
        $this->context = new Context($root);
    }

    public function testMatch()
    {
        $context = $this->context;
        $rule = new Iterate(new NoLogic(false), false);

        $this->assertTrue($rule->match($context));
    }

    public function testExecute()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $rule = new Iterate($ruleMock, false);

        $root = &$context->getRoot();
        $root = array_fill(0, 10, 'value');

        $ruleMock->expects($this->exactly(10))
                ->method('parse')
                ->willReturn(false);

        /** @var Iterate $rule */
        $this->assertTrue($rule->execute($context));
    }

    public function testRecursive()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        /** @var Iterate $rule */
        $rule = new Iterate($ruleMock, true);

        $root = &$context->getRoot();
        $repeat = array_fill(0, 10, 'deep');
        $root = array_fill(0, 10, $repeat);

        $ruleMock->expects($this->exactly(110))
                ->method('parse')
                ->willReturn(false);

        $this->assertTrue($rule->execute($context));
    }

    public function testWithRule()
    {
        $context = $this->context;

        $subRule = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $subRule->expects($this->exactly(10))
                ->method('parse')
                ->willReturn(true);

        /** @var Iterate $rule */
        $rule = new Iterate($subRule, true);

        $root = &$context->getRoot();
        $repeat = array_fill(0, 10, 'deep');
        $root = array_fill(0, 10, $repeat);

        $this->assertTrue($rule->execute($context));
        $this->assertFalse($context->isChanged());
    }

    public function testRecursiveShouldSetChanged()
    {
        $context = $this->context;

        $subRule = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $subRule->expects($this->exactly(2))
                ->method('parse')
                ->willReturnCallback(function(Context $context) {
                    if ('two' === $context->getIndex()) {
                        $context->changed();
                    }

                    return false;
                });

        /** @var Iterate $rule */
        $rule = new Iterate($subRule, true);

        $root = &$context->getRoot();
        $root['one'] = [
            'two' => 'test'
        ];

        $this->assertTrue($rule->execute($context));
        $this->assertTrue($context->isChanged());
    }

}
