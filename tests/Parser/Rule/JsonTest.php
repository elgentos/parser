<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 13:37
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{

    const DATAPATH = PARSERTEST_DATA_DIR;

    /** @var Context */
    private $context;

    /** @var array */
    private $jsonContent;

    public function setUp()
    {
        $root = [
                'json' => file_get_contents(self::DATAPATH . '/jsonImportData.json')
        ];
        $this->context = new Context($root);
        $this->jsonContent = json_decode($root['json'], true);
    }

    public function testParse()
    {
        $context = $this->context;

        $rule = new Json;

        $this->assertTrue($rule->parse($context));
        $this->assertSame($this->jsonContent, $context->getCurrent());
    }

    public function testInvalidType()
    {
        $rule = new Json;

        $root = [['test']];
        $context = new Context($root);

        $this->expectException(RuleInvalidContextException::class);
        $rule->parse($context);
    }

}
