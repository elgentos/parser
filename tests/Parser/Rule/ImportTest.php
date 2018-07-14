<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-7-18
 * Time: 9:10
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsFalse;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use PHPUnit\Framework\TestCase;

class ImportTest extends TestCase
{

    const DATAPATH = __DIR__ . '/data';

    /** @var Context */
    private $context;

    /** @var string */
    private $content;

    public function setUp()
    {
        $root = [
                'import' => '/jsonImportData.json'
        ];
        $this->context = new Context($root);
        $this->content = file_get_contents(self::DATAPATH . $root['import']);
    }

    public function testGetMatcher()
    {
        $rule = new Import(self::DATAPATH);

        $this->assertInstanceOf(IsTrue::class, $rule->getMatcher());

        $rule = new Import(self::DATAPATH, new IsFalse);
        $this->assertInstanceOf(IsFalse::class, $rule->getMatcher());
    }

    public function testParse()
    {
        $context = $this->context;

        $rule = new Import(self::DATAPATH);

        $this->assertTrue($rule->execute($context));
        $this->assertTrue($context->isChanged());
        $this->assertSame($this->content, $context->getCurrent());
    }

    public function testSafepath()
    {
        $context = $this->context;

        $current = &$context->getCurrent();
        $current = '../../../' . $current;

        $rule = new Import(self::DATAPATH . '//../...//.');

        $rule->execute($context);
        $this->assertSame($this->content, $context->getCurrent());
    }

}
