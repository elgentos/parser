<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Dutchlabelshop\Parser\Matcher;

require_once __DIR__ . '/MatcherAbstract.php';

class IsNotTest extends MatcherAbstract
{

    public function testValidate()
    {
        $context = $this->context;
        $matcher = new IsNot(new IsTrue());

        $this->assertFalse($matcher->validate($context));
    }

}
