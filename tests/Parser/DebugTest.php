<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 13:02
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Exceptions\DebugNoLastStoryException;
use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Stories\Factory;
use PHPUnit\Framework\TestCase;

class DebugTest extends TestCase
{

    /** @var Debug */
    private $parserDebug;

    public function setUp()
    {
        $this->parserDebug = new Debug;
    }

    public function testGetLastStoryException()
    {
        $parser = $this->parserDebug;

        $this->expectException(DebugNoLastStoryException::class);
        $parser->getLastStory();
    }

    public function testParse()
    {
        $parser = $this->parserDebug;

        $storyMock = $this->getMockBuilder(Story::class)
                ->disableOriginalConstructor()
                ->setMethods(['parse'])
                ->getMock();

        $storyMock->expects($this->once())
                ->method('parse')
                ->willReturn(true);

        $storiesMock = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        $storiesMock->expects($this->once())
                ->method('getStory')
                ->willReturn($storyMock);

        $data = [];
        $parser->parse($data, $storiesMock);

        return [$parser, $storiesMock];
    }

    /**
     * @depends testParse
     */
    public function testGetLastStory($args)
    {
        list($parser, $storiesMock) = $args;
        $this->assertInstanceOf(get_class($storiesMock), $parser->getLastStory());
    }
}
