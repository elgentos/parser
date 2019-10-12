<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 2-11-18
 * Time: 11:40.
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class AppendTest extends TestCase
{
    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [
                1, 2, 3,
                'append' => [4, 5, 6],
        ];
        $this->context = new Context($root);
    }

    public function testParse()
    {
        $context = $this->context;

        $context->setIndex('append');

        $rule = new Append();
        $this->assertTrue($rule->parse($context));
        $this->assertSame([1, 2, 3, 4, 5, 6], $context->getRoot());
    }
}
