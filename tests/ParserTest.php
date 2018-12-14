<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 13:01
 */

namespace Elgentos;

use Elgentos\Parser\Interfaces\ParserInterface;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{

    public function testReadFile()
    {
        $parserMock = $this->getMockBuilder(ParserInterface::class)
                ->getMock();

        $parserMock->expects($this->once())
            ->method('parse');

        $data = Parser::readFile('test.mock', '.', $parserMock);
        $this->assertSame(['@import' => 'test.mock'], $data);
    }

    public function testReadSimple()
    {
        $parserMock = $this->getMockBuilder(ParserInterface::class)
                ->getMock();

        $parserMock->expects($this->once())
                ->method('parse');

        $data = Parser::readSimple('test.mock', '.', $parserMock);
        $this->assertSame(['@import' => 'test.mock'], $data);
    }


}
