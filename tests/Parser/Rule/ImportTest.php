<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-7-18
 * Time: 9:10
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
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

    public function testParse()
    {
        $context = $this->context;

        $rule = new Import(self::DATAPATH);

        $this->assertTrue($rule->parse($context));
        $this->assertTrue($context->isChanged());
        $this->assertSame($this->content, $context->getCurrent());
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

    public function testInvalidType()
    {
        $rule = new Import('.');

        $root = [['test']];
        $context = new Context($root);

        $this->expectException(RuleInvalidContextException::class);
        $rule->parse($context);
    }

}
