<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28.
 */

namespace Elgentos\Parser\Matcher;

class AnyTest extends MatcherAbstract
{
    public function testConstructorArguments()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Any();
    }

    public function testValidateTrue()
    {
        $context = $this->context;

        $true = new ResolveTrue();

        $matcher = new Any($true, $true);
        $this->assertTrue($matcher->validate($context));
    }

    public function testValidateSomeTrue()
    {
        $context = $this->context;

        $true = new ResolveTrue();
        $false = new ResolveFalse();

        $matcher = new Any($true, $false);
        $this->assertTrue($matcher->validate($context));

        $matcher = new Any($false, $true);
        $this->assertTrue($matcher->validate($context));
    }

    public function testValidateFalse()
    {
        $context = $this->context;

        $false = new ResolveFalse();

        $matcher = new Any($false, $false);
        $this->assertFalse($matcher->validate($context));
    }

    public function testValidateMany()
    {
        $context = $this->context;

        $true = $this->getMockBuilder(ResolveTrue::class)
                ->setMethods(['validate'])
                ->getMock();

        $true->expects($this->exactly(1))
                ->method('validate')
                ->willReturn(true);

        $matcher = new Any($true, $true, $true, $true, $true);
        $this->assertTrue($matcher->validate($context));
    }
}
