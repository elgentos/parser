<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 15:50
 */

namespace Elgentos\Parser;

use PHPUnit\Framework\TestCase;

class StoryBookTest extends TestCase
{

    public function testAddGetStories()
    {
        $story = $this->getMockBuilder(Story::class)
                ->getMock();

        $storyBook = new StoryBook;

        $this->assertSame(0, $storyBook->getStories());
        $storyBook->addStories($story);
        $this->assertSame(1, $storyBook->getStories());
        $storyBook->addStories($story, $story, $story);
        $this->assertSame(4, $storyBook->getStories());
    }

    public function testGetPages()
    {
        $story = $this->getMockBuilder(Story::class)
                ->getMock();

        $story->expects($this->exactly(4))
                ->method('getPages')
                ->willReturn(4);

        $story->expects($this->exactly(4))
                ->method('getRead')
                ->willReturn(3);

        $story->expects($this->exactly(4))
                ->method('getSuccessful')
                ->willReturn(2);

        $storyBook = new StoryBook;

        $this->assertSame(0, $storyBook->getPages());
        $this->assertSame(0, $storyBook->getRead());
        $this->assertSame(0, $storyBook->getSuccessful());

        $storyBook->addStories($story);
        $this->assertSame(4, $storyBook->getPages());
        $this->assertSame(3, $storyBook->getRead());
        $this->assertSame(2, $storyBook->getSuccessful());

        $storyBook->addStories($story, $story);
        $this->assertSame(12, $storyBook->getPages());
        $this->assertSame(9, $storyBook->getRead());
        $this->assertSame(6, $storyBook->getSuccessful());
    }

}
