<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Dutchlabelshop\Parser\Matcher;

use Dutchlabelshop\Parser\Context;

require_once __DIR__ . '/MatcherAbstract.php';

class IsCallbackTest extends MatcherAbstract
{
    public function testValidateTrue()
    {
        $context = $this->context;
        $matcher = new IsCallback(function(Context $context){
            return true;
        });

        $this->assertTrue($matcher->validate($context));
    }

    public function testValidateFalse()
    {
        $context = $this->context;
        $matcher = new IsCallback(function(Context $context){
            return false;
        });

        $this->assertFalse($matcher->validate($context));
    }

}
