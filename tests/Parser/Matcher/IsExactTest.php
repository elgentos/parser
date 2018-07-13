<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:34
 */

namespace Dutchlabelshop\Parser\Matcher;

require_once __DIR__ . '/MatcherAbstract.php';

class IsExactTest extends MatcherAbstract
{

    public function testValidate()
    {
        $context = $this->context;

        $matcher = new IsExact('test');
        $context->setIndex('test');

        $this->assertTrue($matcher->validate($context));
        $context->setIndex('testje');
        $this->assertFalse($matcher->validate($context));
    }

    public function testValidateCurrent()
    {
        $context = $this->context;

        $root = &$context->getRoot();
        $root['test'] = 'value';
        $context->setIndex('test');

        $matcher = new IsExact('value', 'getCurrent');
        $this->assertTrue($matcher->validate($context));
    }
}
