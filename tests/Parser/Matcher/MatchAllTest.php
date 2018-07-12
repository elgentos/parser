<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Dutchlabelshop\Parser\Matcher;

require_once __DIR__ . '/MatcherAbstract.php';

class MatchAllTest extends MatcherAbstract
{

    public function testValidateTrue()
    {
        $context = $this->context;

        $true = new IsTrue;

        $matcher = new MatchAll($true, $true);
        $this->assertTrue($matcher->validate($context));
    }

    public function testValidateFalse()
    {
        $context = $this->context;

        $true = new IsTrue;
        $false = new IsFalse;

        $matcher = new MatchAll($true, $false);
        $this->assertFalse($matcher->validate($context));
    }

    public function testValidateMany()
    {
        $context = $this->context;

        $true = $this->getMockBuilder(IsTrue::class)
                ->setMethods(['validate'])
                ->getMock();

        $true->expects($this->exactly(5))
                ->method('validate')
                ->willReturn(true);

        $matcher = new MatchAll($true, $true, $true, $true, $true);
        $this->assertTrue($matcher->validate($context));
    }

}
