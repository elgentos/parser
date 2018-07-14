<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 0:15
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsArray;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\Matcher\IsFalse;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use PHPUnit\Framework\TestCase;

class MergeTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [
                'merge' => [
                        "test" => "content",
                        "recursive" => [
                                [
                                        "key" => "value"
                                ]
                        ]
                ],
                'from' => [
                        'me' => 'key'
                ],
                "recursive" => [
                    "is" => "gone"
                ]
        ];
        $this->context = new Context($root);
    }

    public function testGetMatcher()
    {
        $rule = new Merge;
        $this->assertInstanceOf(IsArray::class, $rule->getMatcher());

        $rule = new Merge(false, new IsFalse);
        $this->assertInstanceOf(IsFalse::class, $rule->getMatcher());
    }

    public function testRegularMerge()
    {
        $context = $this->context;

        $test = array_merge($context->getCurrent(), $context->getRoot());
        unset($test['merge']);

        $rule = new Merge(false);

        $rule->execute($context);
        $this->assertSame($test, $context->getRoot());
        $this->assertTrue($context->isChanged());
    }

    public function testRecursiveMerge()
    {
        $context = $this->context;

        $test = array_merge_recursive($context->getCurrent(), $context->getRoot());
        unset($test['merge']);

        $rule = new Merge(true);

        $rule->execute($context);
        $this->assertSame($test, $context->getRoot());
        $this->assertTrue($context->isChanged());
    }

    public function testRecursiveMergeShouldNotToArray()
    {
        $context = $this->context;

        $root = &$context->getRoot();
        $root['test'] = 'merge';

        $test = array_merge_recursive($context->getCurrent(), $root);
        unset($test['merge']);

        $test['test'] = 'merge';

        $rule = new Merge(true);

        $rule->execute($context);
        $this->assertSame($test, $context->getRoot());
    }

}
