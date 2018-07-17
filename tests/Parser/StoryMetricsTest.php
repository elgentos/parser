<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 15:50
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Interfaces\RuleInterface;
use PHPUnit\Framework\TestCase;

class StoryMetricsTest extends TestCase
{

    public function testAddGetStories()
    {
        $story = $this->getMockBuilder(Story::class)
                ->disableOriginalConstructor()
                ->getMock();

        $storyBook = new StoryMetrics;

        $this->assertSame(0, $storyBook->getStories());
        $storyBook->addStories($story);
        $this->assertSame(1, $storyBook->getStories());
        $storyBook->addStories($story, $story, $story);
        $this->assertSame(4, $storyBook->getStories());
    }

    public function testGetPages()
    {
        $story = $this->getMockBuilder(Story::class)
                ->disableOriginalConstructor()
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

        $story->expects($this->exactly(4))
                ->method('getCost')
                ->willReturn(1.5);

        $storyBook = new StoryMetrics;

        $this->assertSame(0, $storyBook->getPages());
        $this->assertSame(0, $storyBook->getRead());
        $this->assertSame(0, $storyBook->getSuccessful());
        $this->assertSame(0.0, $storyBook->getCost());

        $storyBook->addStories($story);
        $this->assertSame(4, $storyBook->getPages());
        $this->assertSame(3, $storyBook->getRead());
        $this->assertSame(2, $storyBook->getSuccessful());
        $this->assertSame(1.5, $storyBook->getCost());

        $storyBook->addStories($story, $story);
        $this->assertSame(12, $storyBook->getPages());
        $this->assertSame(9, $storyBook->getRead());
        $this->assertSame(6, $storyBook->getSuccessful());
        $this->assertSame(4.5, $storyBook->getCost());
    }

    public function testCreateStory()
    {
        $storyBook = new StoryMetrics;

        $rule = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $storyBook->createStory('Story 1', $rule);
        $story = $storyBook->createStory('Story 2', $rule);

        $this->assertInstanceOf(Story::class, $story);
        $this->assertSame(2, $storyBook->getStories());
    }

    public function testStatistics()
    {
        $root = [];
        $context = new Context($root);

        $storyBook = new StoryMetrics;

        $rule = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $rule->expects($this->exactly(13))
                ->method('parse')
                ->willReturn(
                        true,
                        false,
                        true,

                        false,
                        true,
                        true,
                        false,
                        true,

                        true,
                        true,
                        true,
                        false,
                        true
                );

        $story1 = $this->getMockBuilder(Story::class)
                ->setConstructorArgs([
                        'Story 1',
                        $rule
                ])
                ->setMethods(['getCost'])
                ->getMock();

        $story2 = $this->getMockBuilder(Story::class)
                ->setConstructorArgs([
                        'Story 2',
                        $rule,
                        $rule
                ])
                ->setMethods(['getCost'])
                ->getMock();;

        $story1->expects($this->any())
                ->method('getCost')
                ->willReturn(1.3);

        $story2->expects($this->any())
                ->method('getCost')
                ->willReturn(1.6);

        /** @var Story $story1 */
        /** @var Story $story2 */
        $storyBook->addStories($story1, $story2);

        // Story 1 3 times
        $story1->parse($context);
        $story1->parse($context);
        $story1->parse($context);

        // Story 2 5 times
        $story2->parse($context);
        $story2->parse($context);
        $story2->parse($context);
        $story2->parse($context);
        $story2->parse($context);

        $this->assertSame([
                '"Story 1" has 1 page(s) and are read 2 of 3 time(s) successfully (1.300ms)',
                '"Story 2" has 2 page(s) and are read 7 of 10 time(s) successfully (1.600ms)',
        ], $storyBook->getStatistics());

        $this->assertSame([
                'Story 1 1 2 3 1.3000',
                'Story 2 2 7 10 1.6000',
        ], $storyBook->getStatistics('%s %d %d %d %01.4f'));
    }

}
