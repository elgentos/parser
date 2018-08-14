<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 0:19
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Interfaces\MatcherInterface;

class ContainsTest extends MatcherAbstract
{

    public function testInstanceOfMatcherInterface()
    {
        $contains = new Contains('test');
        $this->assertInstanceOf(MatcherInterface::class, $contains);
    }

    public function testParseCaseSensitive()
    {
        $contains = new Contains('test');

        $context = $this->context;

        $current = &$context->getCurrent();


        $current = 'somethingselse';
        $this->assertFalse($contains->validate($context));

        $current = 'testing';
        $this->assertTrue($contains->validate($context));
        $current = 'Testing';
        $this->assertFalse($contains->validate($context));
        $current = 'isTest';
        $this->assertFalse($contains->validate($context));
        $current = 'is_test';
        $this->assertTrue($contains->validate($context));
    }

    public function testParseCaseInSensitive()
    {
        $contains = new Contains('Test', false);

        $context = $this->context;

        $current = &$context->getCurrent();

        $current = 'somethingselse';
        $this->assertFalse($contains->validate($context));

        $current = 'testing';
        $this->assertTrue($contains->validate($context));
        $current = 'Testing';
        $this->assertTrue($contains->validate($context));
        $current = 'isTest';
        $this->assertTrue($contains->validate($context));
        $current = 'is_test';
        $this->assertTrue($contains->validate($context));
    }

    public function testMethod()
    {
        $contains = new Contains('Test', false, 'getIndex');

        $context = $this->context;

        $context->setIndex('test');
        $this->assertTrue($contains->validate($context));
    }

}
