<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 13:20
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsFalse;
use Dutchlabelshop\Parser\Matcher\IsTrue;
use PHPUnit\Framework\TestCase;

class YamlTest extends TestCase
{

    /** @var Context */
    private $context;
    /** @var array */
    private $yamlContent;

    public function setUp()
    {
        $root = [
                "test" => file_get_contents(__DIR__ . '/data/jsonImportData.yaml')
        ];
        $this->context = new Context($root);

        $yamlParser = new \Symfony\Component\Yaml\Parser();
        $this->yamlContent = $yamlParser->parse($root['test']);

    }

    public function testGetMatcher()
    {
        $rule = new Yaml;
        $this->assertInstanceOf(IsTrue::class, $rule->getMatcher());

        $rule = new Yaml(new IsFalse);
        $this->assertInstanceOf(IsFalse::class, $rule->getMatcher());
    }

    public function testExecute()
    {
        $context = $this->context;

        $rule = new Yaml;
        $this->assertTrue($rule->execute($context));
        $this->assertSame($this->yamlContent, $context->getCurrent());
    }

}
