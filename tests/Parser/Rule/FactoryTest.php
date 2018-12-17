<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-12-18
 * Time: 12:10
 */

namespace Elgentos\Parser\Rule;

require PARSERTEST_DATA_DIR . '/php/FactoryTestConstrutor.php';
require PARSERTEST_DATA_DIR . '/php/FactoryTestSetters.php';

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{

    public function testConstructor()
    {
        $this->expectException(\ReflectionException::class);

        new Factory(\stdClass::class);
        new Factory('\Elgentos\Parser\Rule\NonExistantClass');
    }

    public function testParseNoArray()
    {
        $factoryRule = new Factory(\stdClass::class);

        $content = [
            'no' => 'array'
        ];
        $context = new Context($content);

        $this->assertFalse($factoryRule->parse($context));
    }

    public function testParse()
    {
        $factoryRule = new Factory(\stdClass::class);

        $content = [[]];
        $context = new Context($content);

        $current = &$context->getCurrent();

        $this->assertTrue($factoryRule->parse($context));
        $this->assertInstanceOf(\stdClass::class, $current);
    }

    public function testParseArgumentsWithDefaults()
    {
        $factoryRule = new Factory(\FactoryTestConstrutor::class, [
            'argument1' => 'default1',
            'argument2' => 'default2'
        ]);

        $content = [[
            'argument2' => 'test2',
            'ignored_argument' => true
        ]];
        $context = new Context($content);

        $this->assertTrue($factoryRule->parse($context));

        $result = $context->getCurrent();

        $this->assertSame('default1', $result->argument1);
        $this->assertSame('test2', $result->argument2);
    }

    public function testParseArguments()
    {
        $factoryRule = new Factory(\FactoryTestConstrutor::class, [
            'argument1', 'argument2'
        ]);

        $content = [[
            'argument2' => 'test2',
            'argument1' => 'test1',
            'ignored_argument' => true
        ]];
        $context = new Context($content);

        $this->assertTrue($factoryRule->parse($context));

        $result = $context->getCurrent();

        $this->assertSame('test1', $result->argument1);
        $this->assertSame('test2', $result->argument2);
    }

    public function testParseSetters()
    {
        $factoryRule = new Factory(\FactoryTestSetters::class, [], [
            'data' => 'setData',
            'noData' => 'setData'
        ]);

        $content = [[
            'data' => 'test3',
            'argument1' => 'test1',
            'ignored_argument' => true
        ]];
        $context = new Context($content);

        $this->assertTrue($factoryRule->parse($context));

        $result = $context->getCurrent();

        $this->assertSame('test3', $result->data);
    }

}
