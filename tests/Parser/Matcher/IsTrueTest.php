<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Dutchlabelshop\Parser\Matcher;

require_once __DIR__ . '/MatcherAbstract.php';

class IsTrueTest extends MatcherAbstract
{
    public function testValidate()
    {
        $context = $this->context;
        $true = new IsTrue();

        $this->assertTrue($true->validate($context));
    }

}
