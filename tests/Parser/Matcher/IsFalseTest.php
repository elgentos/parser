<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Dutchlabelshop\Parser\Matcher;

require_once __DIR__ . '/MatcherAbstract.php';

class IsFalseTest extends MatcherAbstract
{

    public function testValidate()
    {
        $context = $this->context;
        $matcher = new IsFalse();

        $this->assertFalse($matcher->validate($context));
    }

}
