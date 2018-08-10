<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 13:09
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Interfaces\RuleInterface;
use PHPUnit\Framework\TestCase;

class StoryTest extends TestCase
{

    /** @var Context */
    private $context;

    public function setUp()
    {
        $root = [];
        $this->context = new Context($root);
    }

    public function testTitle()
    {
        $test = 'Every story needs a name';
        $story = new Story($test);

        $this->assertSame($test, $story->getName());
    }

    public function testOneStory()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock->expects($this->once())
                ->method('parse')
                ->willReturn(true);

        /** @var RuleInterface $ruleMock */
        $story = new Story('Test one page', $ruleMock);

        $this->assertTrue($story->match($context));
        $this->assertTrue($story->parse($context));
    }

    public function testTwoStories()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock->expects($this->exactly(2))
                ->method('parse')
                ->willReturn(true);

        $story = new Story('Test two pages', $ruleMock, $ruleMock);

        $this->assertTrue($story->parse($context));
    }

    public function testCounters()
    {
        $context = $this->context;

        $ruleMock = $this->getMockBuilder(RuleInterface::class)
                ->getMock();

        $ruleMock->expects($this->exactly(10))
                ->method('parse')
                ->willReturn(
                        true, false, true, false, true,
                        true, false, true, false, true
                );

        $story = new Story('Test counter', $ruleMock, $ruleMock, $ruleMock, $ruleMock, $ruleMock);

        $this->assertTrue($story->parse($context));
        $this->assertSame(3, $story->getSuccessful());
        $this->assertSame(5, $story->getPages());
        $this->assertSame(5, $story->getRead());
        $cost = $story->getCost();
        $this->assertGreaterThan(0, $cost);

        $this->assertTrue($story->parse($context));
        $this->assertSame(6, $story->getSuccessful());
        $this->assertSame(5, $story->getPages());
        $this->assertSame(10, $story->getRead());
        $this->assertGreaterThan($cost, $story->getCost());
    }

}
