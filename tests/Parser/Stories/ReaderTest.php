<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 13:02
 */

namespace Elgentos\Parser\Stories;

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

}
