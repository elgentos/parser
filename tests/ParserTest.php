<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 13:01.
 */

namespace Elgentos;

use Elgentos\Parser\Interfaces\ParserInterface;
use Elgentos\Parser\Interfaces\RuleInterface;
use Elgentos\Parser\Stories\Builder\Factories;
use PHPUnit\Framework\Constraint\IsInstanceOf;
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

    public function testBuildFactories()
    {
        $parserMock = $this->getMockBuilder(ParserInterface::class)
                ->getMock();

        $template = [
            'test' => [
                'class' => ParserInterface::class,
            ],
        ];

        $test = ['@template' => $template];

        $parserMock->expects($this->once())
                ->method('parse')
                ->with($test, new IsInstanceOf(Factories::class));

        $data = Parser::buildFactories($template, false, $parserMock);
        $this->assertSame($test, $data);
    }

    public function testBuildStructure()
    {
        $template = [
            'factory' => [
                'class' => ParserInterface::class,
            ],
        ];

        $factories = [];

        $data = Parser::buildStructure($template, $factories);
        $this->assertInstanceOf(RuleInterface::class, $data);
    }
}
