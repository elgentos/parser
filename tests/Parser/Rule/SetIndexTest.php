<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 22:26
 */

namespace Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsFalse;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use Dutchlabelshop\Parser\Rule\SetIndex;
use PHPUnit\Framework\TestCase;

class SetIndexTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [];
        $this->context = new Context($root);
    }

    public function testGetMatcher()
    {

        $rule = new SetIndex('test');
        $this->assertInstanceOf(IsTrue::class, $rule->getMatcher());

        $rule = new SetIndex('test', new IsFalse);
        $this->assertInstanceOf(IsFalse::class, $rule->getMatcher());
        $this->assertFalse($rule->parse($this->context));
    }

    public function testParse()
    {
        $context = $this->context;

        $rule1 = new SetIndex('test1');
        $rule2 = new SetIndex('test2');

        $this->assertTrue($rule1->parse($context));
        $this->assertSame('test1', $context->getIndex());

        $this->assertTrue($rule2->parse($context));
        $this->assertSame('test2', $context->getIndex());
    }


}
