<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 13:03
 */

namespace Elgentos\Parser\Stories;

use Elgentos\Parser\Exceptions\StoriesExistsException;
use Elgentos\Parser\Exceptions\StoriesInvalidClassException;
use Elgentos\Parser\Exceptions\StoriesNotFoundException;
use Elgentos\Parser\Interfaces\StoriesInterface;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{

    public function testAdd()
    {
        $stories = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        $return = Factory::add('mock', get_class($stories));
        $this->assertNull($return);
    }

    public function testAddException()
    {
        $stories = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        $this->expectException(StoriesExistsException::class);
        Factory::add('mock', get_class($stories));
    }

    public function testSetNonExistent()
    {
        $this->expectException(StoriesInvalidClassException::class);
        Factory::set('mock', '---non-existant--!');
    }

    public function testSet()
    {
        $stories = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        // Should allow overwrite
        $return = Factory::set('mock', get_class($stories));
        $this->assertNull($return);
    }

    public function testAddSingletonException()
    {
        $stories = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        $this->expectException(StoriesExistsException::class);
        Factory::addSingleton('mock', $stories);
    }

    public function testAddSingleton()
    {
        $stories = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        $return = Factory::addSingleton('mock-singleton', $stories);
        $this->assertNull($return);
    }

    public function testSetSingleton()
    {
        $stories = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        // Should allow overwrite
        $return = Factory::setSingleton('mock', $stories);
        $this->assertNull($return);
    }

    public function testCreateNonExistant()
    {
        $this->expectException(StoriesNotFoundException::class);
        Factory::create('non-existant--!');
    }

    public function testCreate()
    {
        $stories = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        $expected = get_class($stories);
        Factory::add('mock-create', $expected);
        $this->assertInstanceOf($expected, Factory::create('mock-create'));
    }

    public function testCreateSingleton()
    {
        $stories = $this->getMockBuilder(StoriesInterface::class)
                ->getMock();

        Factory::addSingleton('mock-create-singleton', $stories);
        $this->assertSame($stories, Factory::create('mock-create-singleton'));
    }

    public function testDefaults()
    {
        $this->assertInstanceOf(Reader::class, Factory::create('reader', '.'));
    }
}
