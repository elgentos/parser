<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 13:20
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Exceptions\RuleSymfonyYamlNotFoundException;
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
                "test" => file_get_contents(PARSERTEST_DATA_DIR . '/jsonImportData.yaml')
        ];
        $this->context = new Context($root);

        $yamlParser = new \Symfony\Component\Yaml\Parser();
        $this->yamlContent = $yamlParser->parse($root['test']);

    }

    public function testParse()
    {
        $context = $this->context;

        $rule = new Yaml;
        $this->assertTrue($rule->parse($context));
        $this->assertSame($this->yamlContent, $context->getCurrent());
    }

    public function testInvalidType()
    {
        $rule = new Yaml;

        $root = [['test']];
        $context = new Context($root);

        $this->expectException(RuleInvalidContextException::class);
        $rule->parse($context);
    }

    public function testNonExistantClass()
    {
        $this->expectException(RuleSymfonyYamlNotFoundException::class);
        new Yaml('non_existant\class');
    }

}
