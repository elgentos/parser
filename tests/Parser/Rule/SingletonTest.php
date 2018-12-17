<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 18-12-18
 * Time: 0:04
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class SingletonTest extends TestCase
{

    public function testParse()
    {
        $factory = new Factory(\stdClass::class);
        $singleton = new Singleton($factory);

        $first = $second = [];

        $content = [
            'first' => &$first,
            'second' => &$second
        ];
        $context = new Context($content);

        $context->setIndex('first');
        $this->assertTrue($singleton->parse($context));

        $context->setIndex('second');
        $this->assertTrue($singleton->parse($context));

        $this->assertInstanceOf(\stdClass::class, $first);
        $this->assertInstanceOf(\stdClass::class, $second);
        $this->assertSame($first, $second);
    }

}
