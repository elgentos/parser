<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 14:44
 */

namespace Dutchlabelshop\Parser\Matcher;

require_once __DIR__ . '/MatcherAbstract.php';

class IsArrayTest extends MatcherAbstract
{

    public function testValidate()
    {
        $context = $this->context;
        $current = &$context->getCurrent();

        $matcher = new IsArray();

        $this->assertFalse($matcher->validate($context));

        $current = ['array'];
        $this->assertTrue($matcher->validate($context));
    }

}