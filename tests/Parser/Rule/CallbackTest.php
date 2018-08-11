<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 1:01
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
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


    public function testParse()
    {
        $context = $this->context;
        $rule = new Callback(function (Context $context): bool {
            return true;
        });

        $this->assertTrue($rule->parse($context));
    }

}
