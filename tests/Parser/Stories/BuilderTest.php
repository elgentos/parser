<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-8-18
 * Time: 1:07
 */

namespace Elgentos\Parser\Stories;

use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{

    public function testShouldImplementStoriesInterface()
    {
        $builder = new Builder;
        $this->assertInstanceOf(StoriesInterface::class, $builder);
    }

    public function testGetStory()
    {
        $builder = new Builder;
        $this->assertInstanceOf(Story::class, $builder->getStory());
    }

    public function testGetMetrics()
    {
        $builder = new Builder;
        $this->assertInstanceOf(StoryMetrics::class, $builder->getMetrics());
    }


}
