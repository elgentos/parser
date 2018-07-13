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
                self::JSON_INDEX => file_get_contents(SELF::DATAPATH . '/jsonImportData.json')
        ];
        $this->context = new Context($root);
        $this->jsonContent = json_decode($root[self::JSON_INDEX], true);
    }

    public function testMatcher()
    {
        $rule = new Json();
        $this->assertInstanceOf(IsExact::class, $rule->getMatcher());

        $rule = new Json(false, new IsTrue);
        $this->assertInstanceOf(IsTrue::class, $rule->getMatcher());
    }

    public function testMatch()
    {
        $context = $this->context;

        $rule = new Json(false);
        $this->assertTrue($rule->match($context));
        $context->setIndex('test');
        $this->assertFalse($rule->match($context));
    }

    public function testParse()
    {
        $context = $this->context;

        $rule = new Json(false);

        $this->assertTrue($rule->parse($context));
        $this->assertSame($this->jsonContent, $context->getRoot());

        $context->setIndex('__notimport');
        $this->assertFalse($rule->parse($context));
    }

    public function testRegularMerge()
    {
        $context = $this->context;

        $root = &$context->getRoot();

        $root['recursive'] = ['test' => 'gone'];

        $test = array_merge($this->jsonContent, $root);
        unset($test[self::JSON_INDEX]);

        $rule = new Json(false);

        $rule->parse($context);
        $this->assertSame($test, $context->getRoot());
    }

    public function testRecursiveMerge()
    {
        $context = $this->context;

        $root = &$context->getRoot();

        $root['recursive'] = ['test' => 'gone'];

        $test = array_merge_recursive($this->jsonContent, $root);
        unset($test[SELF::JSON_INDEX]);

        $rule = new Json(true);

        $rule->parse($context);
        $this->assertSame($test, $context->getRoot());
        $this->assertTrue($context->isChanged());
    }

    public function testRecursiveMergeShouldNotToArray()
    {
        $context = $this->context;

        $root = &$context->getRoot();

        $root['test'] = 'merge';
        $root['recursive'] = ['test' => 'gone'];

        $test = array_merge_recursive($this->jsonContent, $root);
        unset($test[SELF::JSON_INDEX]);

        $test['test'] = 'merge';

        $rule = new Json(true);

        $rule->parse($context);
        $this->assertSame($test, $context->getRoot());
    }

}
