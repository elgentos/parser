<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 15-7-18
 * Time: 2:04.
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use PHPUnit\Framework\TestCase;

class TrimTest extends TestCase
{
    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [
                "
                
                remove all space\t
                
                ",
                'ddddcharlistddd',
        ];
        $this->context = new Context($root);
    }

    public function testParse()
    {
        $context = $this->context;

        $rule = new Trim();
        $test = trim($context->getCurrent());

        $rule->parse($context);
        $this->assertSame($test, $context->getCurrent());
    }

    public function testCharlist()
    {
        $context = $this->context;
        $context->setIndex('1');

        $rule = new Trim('d');
        $test = trim($context->getCurrent(), 'd');

        $rule->parse($context);
        $this->assertSame($test, $context->getCurrent());
    }

    public function testInvalidType()
    {
        $rule = new Trim();

        $root = [['test']];
        $context = new Context($root);

        $this->expectException(RuleInvalidContextException::class);
        $rule->parse($context);
    }
}
