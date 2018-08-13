<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Elgentos\Parser\Matcher;

class ResolveTrueTest extends MatcherAbstract
{
    public function testValidate()
    {
        $context = $this->context;
        $matcher = new ResolveTrue();

        $this->assertTrue($matcher->validate($context));
    }

}
