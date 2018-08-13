<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:34
 */

namespace Elgentos\Parser\Matcher;

class ExactTest extends MatcherAbstract
{

    public function testValidate()
    {
        $context = $this->context;

        $matcher = new Exact('test', 'getIndex');
        $context->setIndex('test');

        $this->assertTrue($matcher->validate($context));
        $context->setIndex('testje');
        $this->assertFalse($matcher->validate($context));
    }

    public function testValidateBool()
    {
        $context = $this->context;

        $root = &$context->getRoot();
        $root['test'] = true;
        $context->setIndex('test');

        $matcher = new Exact(true);
        $this->assertTrue($matcher->validate($context));
    }

    public function testValidateInt()
    {
        $context = $this->context;

        $root = &$context->getRoot();
        $root['test'] = 123;
        $context->setIndex('test');

        $matcher = new Exact(123);
        $this->assertTrue($matcher->validate($context));
        $root['test'] = 123.12;
        $this->assertFalse($matcher->validate($context));
    }

    public function testValidateCurrent()
    {
        $context = $this->context;

        $root = &$context->getRoot();
        $root['test'] = 'value';
        $context->setIndex('test');

        $matcher = new Exact('value');
        $this->assertTrue($matcher->validate($context));
    }
}
