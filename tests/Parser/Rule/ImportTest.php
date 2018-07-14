<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-7-18
 * Time: 9:10
 */

namespace Dutchlabelshop\Parser\Rule;


use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use PHPUnit\Framework\TestCase;

class ImportTest extends TestCase
{

    const DATAPATH = __DIR__ . '/data';
    const INDEX = '__import';

    /** @var Context */
    private $context;

    /** @var string */
    private $content;

    public function setUp()
    {
        $root = [
                self::INDEX => '/jsonImportData.json'
        ];
        $this->context = new Context($root);
        $this->content = file_get_contents(self::DATAPATH . $root[self::INDEX]);
    }

    public function testGetMatcher()
    {
        $rule = new Import(self::DATAPATH);

        $this->assertInstanceOf(IsExact::class, $rule->getMatcher());

        $rule = new Import(self::DATAPATH, new IsTrue);
        $this->assertInstanceOf(IsTrue::class, $rule->getMatcher());
    }

    public function testMatch()
    {
        $context = $this->context;

        $rule = new Import(self::DATAPATH);
        $this->assertTrue($rule->match($context));
        $context->setIndex('test');
        $this->assertFalse($rule->match($context));
    }

    public function testParse()
    {
        $context = $this->context;

        $rule = new Import(self::DATAPATH);

        $this->assertTrue($rule->parse($context));
        $this->assertTrue($context->isChanged());
        $this->assertSame($this->content, $context->getCurrent());

        $context->setIndex('__notimport');
        $this->assertFalse($rule->parse($context));
    }

    public function testSafepath()
    {
        $context = $this->context;

        $current = &$context->getCurrent();
        $current = '../../../' . $current;

        $rule = new Import(self::DATAPATH . '//../...//.');

        $rule->parse($context);
        $this->assertSame($this->content, $context->getCurrent());
    }

}
