<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-7-18
 * Time: 23:28
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;
use PHPUnit\Framework\TestCase;

class ChangedTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp(): void
    {
        $root = [];
        $this->context = new Context($root);
    }

    public function testParse()
    {
        $context = $this->context;

        $subRule = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $subRule->expects($this->once())
                ->method('parse')
                ->willReturn(true);

        $rule = new Changed($subRule);
        $rule->parse($context);
        $this->assertSame(1, $rule->getCounter());
    }

    public function testParseChanged()
    {
        $context = $this->context;

        $subRule = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $subRule->expects($this->exactly(2))
                ->method('parse')
                ->willReturn(true);

        $rule = new Changed($subRule);
        $context->changed();
        $rule->parse($context);

        $this->assertSame(2, $rule->getCounter());
    }
}
