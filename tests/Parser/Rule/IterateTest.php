<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 10:08
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsFalse;
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
        $rule = new Iterate();

        $this->assertTrue($rule->match($context));
    }

    public function testMatcherFalse()
    {
        $context = $this->context;
        $rule = new Iterate(false, new IsFalse());

        $this->assertFalse($rule->match($context));
        $this->assertFalse($rule->parse($context));
    }

    public function testParse()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(Iterate::class)
                ->setMethods(['executeRule'])
                ->getMock();

        $rule = new $ruleMock(false);

        $root = &$context->getRoot();
        $root = array_fill(0, 10, 'value');

        $rule->expects($this->exactly(10))
                ->method('executeRule')
                ->willReturn(false);

        /** @var Iterate $rule */
        $this->assertTrue($rule->parse($context));
    }

    public function testParseRecursive()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(Iterate::class)
                ->setMethods(['executeRule'])
                ->getMock();

        /** @var Iterate $rule */
        $rule = new $ruleMock(true);

        $root = &$context->getRoot();
        $repeat = array_fill(0, 10, 'deep');
        $root = array_fill(0, 10, $repeat);

        $rule->expects($this->exactly(100))
                ->method('executeRule')
                ->willReturn(false);

        $this->assertTrue($rule->parse($context));
    }

}
