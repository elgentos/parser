<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 11-8-18
 * Time: 22:15
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Interfaces\RuleInterface;
use PHPUnit\Framework\TestCase;

class XmlTest extends TestCase
{

    public function testImplementsRuleInterface()
    {
        $xml = new Xml;

        $this->assertInstanceOf(RuleInterface::class, $xml);
    }

    public function testInvalidType()
    {
        $data = [
                true
        ];

        $result = [
                true
        ];

        $context = new Context($data);

        $xml = new Xml;

        $this->assertFalse($xml->parse($context));
        $this->assertSame($data, $result);
    }

    public function testParse()
    {
        $data = [
                <<<XML
<xml>
    <person state="state1">
        <name>Person1</name>
        <age>10</age>
    </person>
    <otherperson></otherperson>
    <person>
        <age>20</age>
        <name state="state2">Person2</name>
        <age>30</age>
    </person>
</xml>
XML
        ];

        $result = [
            [
                'person' => [
                    [
                        '@attributes' => [
                            'state' => 'state1'
                        ],
                        'name' => 'Person1',
                        'age' => '10'
                    ],
                    [
                        'age' => [
                                '20',
                                '30'
                        ],
                        'name' => [
                            '@attributes' => [
                                'state' => 'state2'
                            ],
                            '@value' => 'Person2'
                        ],
                    ],
                ],
                'otherperson' => '',
            ]
        ];

        $context = new Context($data);

        $xml = new Xml;

        $this->assertTrue($xml->parse($context));
        $this->assertSame($result, $data);
    }

    public function testParseEmpty()
    {
        $data = [
                <<<XML
<xml>
</xml>
XML
        ];

        $result = [["\n"]];

        $context = new Context($data);

        $xml = new Xml;

        $this->assertTrue($xml->parse($context));
        $this->assertSame($result, $data);
    }

}
