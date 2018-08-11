<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 23:19
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
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

    public function testExecute()
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

        $rule = new Explode(',');
        $this->assertTrue($rule->parse($context));
        $this->assertSame($test, $context->getCurrent());
    }

}
