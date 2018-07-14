<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 13:37
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\Matcher\IsFalse;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{

    const DATAPATH = __DIR__ . '/data';
    const JSON_INDEX = '__json';

    /** @var Context */
    private $context;

    /** @var array */
    private $jsonContent;

    public function setUp()
    {
        $root = [
                self::JSON_INDEX => file_get_contents(self::DATAPATH . '/jsonImportData.json')
        ];
        $this->context = new Context($root);
        $this->jsonContent = json_decode($root[self::JSON_INDEX], true);
    }

    public function testMatcher()
    {
        $rule = new Json;
        $this->assertInstanceOf(IsTrue::class, $rule->getMatcher());

        $rule = new Json(new IsFalse);
        $this->assertInstanceOf(IsFalse::class, $rule->getMatcher());
    }

    public function testParse()
    {
        $context = $this->context;

        $rule = new Json;

        $this->assertTrue($rule->execute($context));
        $this->assertSame($this->jsonContent, $context->getCurrent());
    }

}
