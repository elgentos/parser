<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 9:37
 */

namespace Dutchlabelshop\Parser;

use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{

    public function testGetRoot()
    {
        $root = [
                'key' => 'value'
        ];
        $context = new Context($root);

        $reference = &$context->getRoot();

        $this->assertSame($root, $reference);

        // Test references
        $reference['key2'] = 'value2';
        $this->assertSame($root, $reference);
    }

    public function testGetIndex()
    {
        $root = [
                'key' => 'value'
        ];
        $context = new Context($root);

        $this->assertSame('key', $context->getIndex());
    }

    public function testSetIndex()
    {
        $root = [
                'key1' => 'value1',
                'key2' => 'value2'
        ];
        $context = new Context($root);

        $context->setIndex('key2');
        $this->assertSame('key2', $context->getIndex());
    }

    public function testGetCurrent()
    {
        $root = [
                'key1' => 'value1',
                'key2' => 'value2'
        ];
        $context = new Context($root);

        $this->assertSame('value1', $context->getCurrent());

        $context->setIndex('key2');
        $this->assertSame('value2', $context->getCurrent());
    }
}
