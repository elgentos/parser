<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 13:02
 */

namespace Elgentos\Parser\Stories\Reader;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    public function testIntegration()
    {
        $reader = new Simple(PARSERTEST_DATA_DIR);

        $data = [
            '@import' => 'base.yaml'
        ];

        $result = [
            'base' => [
                ['context' => 'YAML'],
                [
                    '@import' => 'json.json',
                ],
                [
                    '@import' => 'xml.xml',
                ],
                [
                    '@import-dir' => 'texts'
                ],

                [
                    '@import-dir' => 'glob'
                ]
            ],
            'second' => [
                '@import' => 'texts/text1.txt'
            ],
            'csv' => [
                    [
                            '@import' => 'csv/1-one.csv'
                    ],
                    [
                        '@import' => 'csv/2-two.csv'
                    ],
            ],
            'csvdir' => [
                '@import-dir' => 'csv'
            ],
        ];

        $context = new Context($data);

        $reader->getStory()
                ->parse($context);

        $this->assertSame($result, $data);
    }
}
