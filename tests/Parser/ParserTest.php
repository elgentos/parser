<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 13:01
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Interfaces\ParserInterface;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{

    public function testReadFile()
    {
        $parserMock = $this->getMockBuilder(ParserInterface::class)
                ->getMock();

        $data = Parser::readFile('test.mock', '.', $parserMock);
        $this->assertSame(['@import' => 'test.mock'], $data);
    }
}
