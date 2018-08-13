<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Elgentos\Parser\Matcher;

require_once __DIR__ . '/MatcherAbstract.php';

class NotTest extends MatcherAbstract
{

    public function testValidate()
    {
        $context = $this->context;
        $matcher = new Not(new ResolveTrue());

        $this->assertFalse($matcher->validate($context));
    }

}
