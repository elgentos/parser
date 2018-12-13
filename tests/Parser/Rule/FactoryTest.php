<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-12-18
 * Time: 12:10
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp()
    {
        $content = [
            []
        ];
        $this->context = new Context($content);
    }

    public function testConstructor()
    {
        $this->expectException(\ReflectionException::class);

        new Factory(\stdClass::class);
        new Factory('\Elgentos\Parser\Rule\NonExistantClass');
    }

    public function testParse()
    {
        $factoryRule = new Factory(\stdClass::class);

        $current = &$this->context->getCurrent();

        $this->assertTrue($factoryRule->parse($this->context));
        $this->assertInstanceOf(\stdClass::class, $current);
    }

}
