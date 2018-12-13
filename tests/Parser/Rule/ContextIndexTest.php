<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-12-18
 * Time: 21:27
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class ContextIndexTest extends TestCase
{

    public function testParse()
    {
        $content = [];
        $context = new Context($content);

        $contextIndexRule = new ContextIndex('newIndex');

        $this->assertTrue($contextIndexRule->parse($context));
        $this->assertSame('newIndex', $context->getIndex());
    }

}
