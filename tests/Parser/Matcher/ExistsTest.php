<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 13:02
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class ExistsTest extends TestCase
{
    public function testValidateEmpty()
    {
        $root = [];
        $context = new Context($root);

        $matcher = new Exists;

        $this->assertFalse($matcher->validate($context));
    }

    public function testValidate()
    {
        $root = [
                'test' => 'test',
                'test2' => 'test',
                'test3' => 'test',
        ];
        $context = new Context($root);

        $matcher = new Exists;
        $this->assertTrue($matcher->validate($context));

        $context->setIndex('non-existant');
        $this->assertFalse($matcher->validate($context));

        $context->setIndex('test2');
        $this->assertTrue($matcher->validate($context));
    }
}
