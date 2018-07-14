<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 23:19
 */

namespace Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\Matcher\IsFalse;
use Dutchlabelshop\Parser\Rule\Explode;
use PHPUnit\Framework\TestCase;

class ExplodeTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [
                '__explode' =>
                        '"first1","first2","first3"' . "\n" .
                        '"second1","second2"' . "\n"
        ];
        $this->context = new Context($root);
    }

    public function testGetMatcher()
    {
        $rule = new Explode;
        $this->assertInstanceOf(IsExact::class, $rule->getMatcher());

        $rule = new Explode(new IsFalse);
        $this->assertInstanceOf(IsFalse::class, $rule->getMatcher());
        $this->assertFalse($rule->parse($this->context));
    }

    public function testParse()
    {
        $context = $this->context;
        $test = explode("\n", $context->getCurrent());

        $rule = new Explode;
        $this->assertTrue($rule->parse($context));
        $this->assertSame($test, $context->getCurrent());
    }

    public function testParseWithComma()
    {
        $context = $this->context;
        $test = explode(',', $context->getCurrent());

        $rule = new Explode(null, ',');
        $this->assertTrue($rule->parse($context));
        $this->assertSame($test, $context->getCurrent());
    }

}