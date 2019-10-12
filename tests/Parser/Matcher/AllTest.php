<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Elgentos\Parser\Matcher;

class AllTest extends MatcherAbstract
{
    public function testConstructorArguments()
    {
        $this->expectException(\InvalidArgumentException::class);
        new All();
    }

    public function testValidateTrue()
    {
        $context = $this->context;

        $true = new ResolveTrue;

        $matcher = new All($true, $true);
        $this->assertTrue($matcher->validate($context));
    }

    public function testValidateFalse()
    {
        $context = $this->context;

        $true = new ResolveTrue;
        $false = new ResolveFalse;

        $matcher = new All($true, $false);
        $this->assertFalse($matcher->validate($context));
    }

    public function testValidateMany()
    {
        $context = $this->context;

        $true = $this->getMockBuilder(ResolveTrue::class)
                ->setMethods(['validate'])
                ->getMock();

        $true->expects($this->exactly(5))
                ->method('validate')
                ->willReturn(true);

        $matcher = new All($true, $true, $true, $true, $true);
        $this->assertTrue($matcher->validate($context));
    }
}
