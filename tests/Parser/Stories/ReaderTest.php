<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 13:02
 */

namespace Elgentos\Parser\Stories;

use Elgentos\Parser\Context;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{

    public function testGetStory()
    {
        $reader = new Reader(__DIR__);
        $this->assertInstanceOf(Story::class, $reader->getStory());
    }

    public function testGetMetrics()
    {
        $reader = new Reader(__DIR__);
        $this->assertInstanceOf(StoryMetrics::class, $reader->getMetrics());
    }

    public function test__construct()
    {
        $readerMock = $this->getMockBuilder(Reader::class)
                ->setMethods(['initStory'])
                ->getMock();

        $readerMock->expects($this->exactly(2))
                ->method('initStory')
                ->withConsecutive(['.'], ['./']);

        $readerMock->__construct();
        $readerMock->__construct('./');
    }

    public function testIntegration()
    {
        $reader = new Reader(__DIR__ . '/data');

        $data = [
            '@import' => 'base.yml'
        ];

        $result = [
            'base' => [
                ['context' => 'YAML'],
                [
                    'json' => [
                        ['context' => 'JSON'],
                        [
                            ['@import' => 'CSV'],
                            ['text' => 'This is a TEXT']
                        ]
                    ]
                ],
                ['text' => 'TEXT4'],
                [
                    'globtext' => [
                        ['text' => 'TEXT1'],
                        ['text' => 'TEXT2'],
                    ]
                ]
            ]
        ];

        $context = new Context($data);

        $reader->getStory()
                ->parse($context);

        $this->assertSame($result, $data);
    }

}
