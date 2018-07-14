<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 1:01
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsFalse;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [];
        $this->context = new Context($root);
    }


    public function testExecute()
    {
        $context = $this->context;
        $rule = new Callback(function (Context $context): bool {
            return true;
        });

        $this->assertTrue($rule->execute($context));
    }

    public function testGetMatcher()
    {
        $rule = new Callback(function () {});
        $this->assertInstanceOf(IsTrue::class, $rule->getMatcher());

        $rule = new Callback(function () {}, new IsFalse);
        $this->assertInstanceOf(IsFalse::class, $rule->getMatcher());
    }
}
