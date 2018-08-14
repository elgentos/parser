<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 11:48
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\MatcherInterface;
use PHPUnit\Framework\TestCase;

class CoreAbstractTest extends TestCase
{

    public function testShouldImplementMatcherInterface()
    {
        $stringAbstract = $this->getMockBuilder(CoreAbstract::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertInstanceOf(MatcherInterface::class, $stringAbstract);
    }

    public function testOptionalArguments()
    {
        $stringAbstract = $this->getMockBuilder(CoreAbstract::class)
                ->setConstructorArgs([''])
                ->getMock();
        $this->assertInstanceOf(MatcherInterface::class, $stringAbstract);

    }

    public function testValidate()
    {
        $stringAbstract = $this->getMockBuilder(CoreAbstract::class)
                ->setMethods(['execute'])
                ->setConstructorArgs(['test'])
                ->getMock();

        $stringAbstract->expects($this->once())
                ->method('execute')
                ->with('testing');

        $root = ['testing'];
        $context = new Context($root);

        $stringAbstract->validate($context);

    }

    public function testCaseInSensitive()
    {
        $stringAbstract = $this->getMockBuilder(CoreAbstract::class)
                ->setMethods(['execute'])
                ->setConstructorArgs(['Test', false])
                ->getMock();

        $reflectionValue = new \ReflectionProperty($stringAbstract, 'needle');
        $reflectionValue->setAccessible(true);

        $this->assertSame('test', $reflectionValue->getValue($stringAbstract));

        $stringAbstract->expects($this->once())
                ->method('execute')
                ->with('testing');

        $root = ['tesTing'];
        $context = new Context($root);

        $stringAbstract->validate($context);
    }

    public function testGetIndex()
    {
        $stringAbstract = $this->getMockBuilder(CoreAbstract::class)
                ->setMethods(['execute'])
                ->setConstructorArgs(['Test', false, 'getIndex'])
                ->getMock();

        $stringAbstract->expects($this->once())
                ->method('execute')
                ->with('testing');

        $root = [
                'tesTing' => 'answer'
        ];
        $context = new Context($root);

        $stringAbstract->validate($context);
    }

    public function testOtherTypeDisableCaseInsensitive()
    {
        $stringAbstract = $this->getMockBuilder(CoreAbstract::class)
                ->setMethods(['execute'])
                ->setConstructorArgs([true, false])
                ->getMock();

        $reflectionValue = new \ReflectionProperty($stringAbstract, 'caseSensitive');
        $reflectionValue->setAccessible(true);

        $this->assertTrue($reflectionValue->getValue($stringAbstract));
    }

}
