<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 20:56
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsFalse;
use Dutchlabelshop\Parser\Matcher\MatchAll;
use PHPUnit\Framework\TestCase;

class CsvTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [
                '__csv' => [
                        '"first1","first2","first3"',
                        '"second1","second2"',
                        '"third1","third2","third3","third4"',
                        '"fourth1","fourth2","fourth3","fourth4","fourth5"',
                ]
        ];
        $this->context = new Context($root);
    }

    public function testMatcher()
    {
        $context = $this->context;

        $rule = new Csv;
        $this->assertInstanceOf(MatchAll::class, $rule->getMatcher());

        $rule = new Csv(new IsFalse);
        $this->assertInstanceOf(IsFalse::class, $rule->getMatcher());
        $this->assertSame(false, $rule->parse($context));
    }

    public function testEmptyContext()
    {
        $root = [
                '__csv' => []
        ];
        $context = new Context($root);

        $rule = new Csv;
        $this->assertFalse($rule->parse($context));
        $this->assertFalse($context->isChanged());
    }

    public function testSmallContextForFirstKeys()
    {
        $root = [
                '__csv' => [
                        ['key1', 'key2']
                ]
        ];
        $context = new Context($root);

        $rule = new Csv(null, true);
        $this->assertFalse($rule->parse($context));
        $this->assertFalse($context->isChanged());
    }

    public function testParse()
    {
        $context = $this->context;
        $result = [
                [
                        'first1',
                        'first2',
                        'first3',
                ],
                [
                        'second1',
                        'second2',
                ],
                [
                        'third1',
                        'third2',
                        'third3',
                        'third4',
                ],
                [
                        'fourth1',
                        'fourth2',
                        'fourth3',
                        'fourth4',
                        'fourth5',
                ]
        ];

        $rule = new Csv;

        $rule->parse($context);
        $this->assertSame($result, $context->getCurrent());
        $this->assertTrue($context->isChanged());
    }

    public function testFirstHasKeys()
    {
        $context = $this->context;
        $result = [
                [
                        'first1' => 'second1',
                        'first2' => 'second2',
                        'first3' => null,
                        3 => null,
                        4 => null,
                ],
                [
                        'first1' => 'third1',
                        'first2' => 'third2',
                        'first3' => 'third3',
                        3 => 'third4',
                        4 => null,
                ],
                [
                        'first1' => 'fourth1',
                        'first2' => 'fourth2',
                        'first3' => 'fourth3',
                        3 => 'fourth4',
                        4 => 'fourth5',
                ]
        ];

        $rule = new Csv(null, true);

        $rule->parse($context);
        $this->assertSame($result, $context->getCurrent());
        $this->assertTrue($context->isChanged());
    }

}
