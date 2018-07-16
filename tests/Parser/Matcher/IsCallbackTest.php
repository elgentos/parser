<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Elgentos\Parser\Matcher;

use Elgentos\Parser\Context;

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
