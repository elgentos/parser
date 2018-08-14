<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:34
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class ExactTest extends TestCase
{

    public function testValidate()
    {
        $root = ['test'];
        $context = new Context($root);
        $current = &$context->getCurrent();

        $matcher = new Exact('test');

        $this->assertTrue($matcher->validate($context));
        $current = 'testje';
        $this->assertFalse($matcher->validate($context));
    }

    public function testValidateBool()
    {
        $root = [true];
        $context = new Context($root);

        $matcher = new Exact(true);
        $this->assertTrue($matcher->validate($context));
    }

    public function testValidateInt()
    {
        $root = [123];
        $context = new Context($root);

        $matcher = new Exact(123);
        $this->assertTrue($matcher->validate($context));
        $root[0] = 123.12;
        $this->assertFalse($matcher->validate($context));
    }

    public function testValidateCurrent()
    {
        $root = ['value'];
        $context = new Context($root);

        $matcher = new Exact('value');
        $this->assertTrue($matcher->validate($context));
        $root[0] = 123.12;
        $this->assertFalse($matcher->validate($context));
    }
}
