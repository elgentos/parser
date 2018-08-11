<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 0:15
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class MergeDownTest extends TestCase
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

    public function testRegularMerge()
    {
        $context = $this->context;

        $test = array_merge($context->getCurrent(), $context->getRoot());
        unset($test['merge']);

        $rule = new MergeDown(false);

        $rule->parse($context);
        $this->assertSame($test, $context->getRoot());
        $this->assertTrue($context->isChanged());
    }

    public function testRecursiveMerge()
    {
        $context = $this->context;

        $test = array_merge_recursive($context->getCurrent(), $context->getRoot());
        unset($test['merge']);

        $rule = new MergeDown(true);

        $rule->parse($context);
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

        $rule = new MergeDown(true);

        $rule->parse($context);
        $this->assertSame($test, $context->getRoot());
    }

    public function testResetIndex()
    {
        $context = $this->context;

        $rule = new MergeDown(false);

        $rule->parse($context);
        $this->assertSame('test', $context->getIndex());
    }

}
