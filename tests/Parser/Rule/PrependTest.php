<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 2-11-18
 * Time: 11:40
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class PrependTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp(): void
    {
        $root = [
                1, 2, 3,
                "prepend" => [4, 5, 6]
        ];
        $this->context = new Context($root);
    }


    public function testParse()
    {
        $context = $this->context;

        $context->setIndex('prepend');

        $rule = new Prepend;
        $this->assertTrue($rule->parse($context));
        $this->assertSame([4,5,6,1,2,3], $context->getRoot());
    }
}
