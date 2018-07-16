<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 22:55
 */

namespace Parser\Matcher;

require_once __DIR__ . '/MatcherAbstract.php';

use Elgentos\Parser\Matcher\IsNull;
use Elgentos\Parser\Matcher\MatcherAbstract;
use PHPUnit\Framework\TestCase;

class IsNullTest extends MatcherAbstract
{

    public function testValidate()
    {
        $context = $this->context;

        $matcher = new IsNull;

        $context->setIndex('test');
        $this->assertTrue($matcher->validate($context));

        $current = &$context->getCurrent();
        $current = 'test';
        $this->assertFalse($matcher->validate($context));
    }

}
