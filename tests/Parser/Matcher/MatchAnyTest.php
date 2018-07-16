<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Elgentos\Parser\Matcher;

class MatchAnyTest extends MatcherAbstract
{

    public function testConstructorArguments()
    {
        $this->expectException(\InvalidArgumentException::class);
        new MatchAny();
    }

    public function testValidateTrue()
    {
        $context = $this->context;

        $true = new IsTrue;

        $matcher = new MatchAny($true, $true);
        $this->assertTrue($matcher->validate($context));
    }

    public function testValidateSomeTrue()
    {
        $context = $this->context;

        $true = new IsTrue;
        $false = new IsFalse;

        $matcher = new MatchAny($true, $false);
        $this->assertTrue($matcher->validate($context));

        $matcher = new MatchAny($false, $true);
        $this->assertTrue($matcher->validate($context));
    }


    public function testValidateFalse()
    {
        $context = $this->context;

        $false = new IsFalse;

        $matcher = new MatchAny($false, $false);
        $this->assertFalse($matcher->validate($context));
    }

    public function testValidateMany()
    {
        $context = $this->context;

        $true = $this->getMockBuilder(IsTrue::class)
                ->setMethods(['validate'])
                ->getMock();

        $true->expects($this->exactly(1))
                ->method('validate')
                ->willReturn(true);

        $matcher = new MatchAny($true, $true, $true, $true, $true);
        $this->assertTrue($matcher->validate($context));
    }

}
