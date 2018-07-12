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

class JsonImportTest extends TestCase
{

    const DATAPATH = __DIR__ . '/data';

    /** @var Context */
    private $context;

    /** @var array */
    private $jsonContent;

    public function setUp()
    {
        $root = [
                '__import' => 'jsonImportData.json'
        ];
        $this->context = new Context($root);

        $this->jsonContent = json_decode(file_get_contents(__DIR__ . '/data/jsonImportData.json'), true);
    }

    public function testMatcher()
    {
        $rule = new JsonImport(self::DATAPATH);
        $this->assertInstanceOf(IsExact::class, $rule->getMatcher());

        $rule = new JsonImport(self::DATAPATH ,false, new IsTrue);
        $this->assertInstanceOf(IsTrue::class, $rule->getMatcher());
    }

    public function testMatch()
    {
        $context = $this->context;

        $rule = new JsonImport(self::DATAPATH ,false);
        $this->assertTrue($rule->match($context));
        $context->setIndex('test');
        $this->assertFalse($rule->match($context));
    }

    public function testParse()
    {
        $context = $this->context;

        $rule = new JsonImport(self::DATAPATH ,false);

        $this->assertFalse($rule->parse($context));
        $this->assertSame($this->jsonContent, $context->getRoot());

        $context->setIndex('__notimport');
        $this->assertFalse($rule->parse($context));
    }

    public function testSafepath()
    {
        $context = $this->context;

        $current = &$context->getCurrent();
        $current = '../../../' . $current;

        $rule = new JsonImport(self::DATAPATH . '//../...//.',false);

        $rule->parse($context);
        $this->assertSame($this->jsonContent, $context->getRoot());
    }

    public function testRegularMerge()
    {
        $context = $this->context;

        $root = &$context->getRoot();

        $root['recursive'] = ['test' => 'gone'];

        $test = array_merge($this->jsonContent, $root);
        unset($test['__import']);

        $rule = new JsonImport(self::DATAPATH . '//../...//.',false);

        $rule->parse($context);
        $this->assertSame($test, $context->getRoot());
    }

    public function testRecursiveMerge()
    {
        $context = $this->context;

        $root = &$context->getRoot();

        $root['recursive'] = ['test' => 'gone'];

        $test = array_merge_recursive($this->jsonContent, $root);
        unset($test['__import']);

        $rule = new JsonImport(self::DATAPATH . '//../...//.',true);

        $rule->parse($context);
        $this->assertSame($test, $context->getRoot());
    }

    public function testRecursiveMergeShouldNotToArray()
    {
        $context = $this->context;

        $root = &$context->getRoot();

        $root['test'] = 'merge';
        $root['recursive'] = ['test' => 'gone'];

        $test = array_merge_recursive($this->jsonContent, $root);
        unset($test['__import']);

        $test['test'] = 'merge';

        $rule = new JsonImport(self::DATAPATH . '//../...//.',true);

        $rule->parse($context);
        $this->assertSame($test, $context->getRoot());
    }


}
