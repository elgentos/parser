<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Elgentos\Parser\Matcher;

class IsRegExpTest extends MatcherAbstract
{

    public function testValidate()
    {
        $context = $this->context;
        $true = new IsRegExp("#^\d+$#", 'getIndex');

        $this->assertFalse($true->validate($context));
        $context->setIndex('132');
        $this->assertTrue($true->validate($context));
    }

    public function testValidateCurrent()
    {
        $context = $this->context;
        $true = new IsRegExp("#^\d+$#");

        $root = &$context->getRoot();

        $root['test'] = 'nomatch';
        $context->setIndex('test');

        $this->assertFalse($true->validate($context));
        $root['test'] = '123';
        $this->assertTrue($true->validate($context));
    }

}
