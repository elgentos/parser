<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 13:01
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Interfaces\StoriesInterface;
use Elgentos\Parser\Stories\Factory;
use PHPUnit\Framework\TestCase;

class StandardTest extends TestCase
{
    public function testParse()
    {
        $storiesMock = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        $standardParser = new Standard;

        $storiesMock->expects($this->once())
                ->method('getStory');

        $data = ['test' => 'test'];
        $return = $standardParser->parse($data, $storiesMock);

        $this->assertNull($return);
    }
}
