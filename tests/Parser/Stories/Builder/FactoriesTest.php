<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-12-18
 * Time: 15:31
 */

namespace Elgentos\Parser\Stories\Builder;

require_once PARSERTEST_DATA_DIR . '/php/FactoryTestConstrutor.php';
require_once PARSERTEST_DATA_DIR . '/php/FactoryTestSetters.php';

use Elgentos\Parser\Context;
use Elgentos\Parser\Rule\Factory;
use Elgentos\Parser\Rule\NoLogic;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;
use PHPUnit\Framework\TestCase;

class FactoriesTest extends TestCase
{

    public function testGetMetrics()
    {
        $factories = new Factories;
        $this->assertInstanceOf(StoryMetrics::class, $factories->getMetrics());
    }

    public function testGetStory()
    {
        $factories = new Factories;
        $this->assertInstanceOf(Story::class, $factories->getStory());
    }

    public function testMetrics()
    {
        $factories = new Factories;

        $metrics = $factories->getMetrics();

        $this->assertSame([
            '0-root',
            '1-iterate',
            '2-factory',
            '3-final'
        ], $metrics->getStatistics('%s'));
    }

    public function testBuilderConstructor()
    {
        $content = [
            'first' => [
                'class' => NoLogic::class,
                'arguments' => [
                    'return' => true
                ]
            ]
        ];

        $data = [&$content];
        $context = new Context($data);

        $factories = new Factories;

        $factories->getStory()->parse($context);

        /** @var Factory $factory */
        $factory = $content['first'];

        $this->assertInstanceOf(Factory::class, $factory);

        $noLogic = [];
        $empty = [&$noLogic];
        $emptyContext = new Context($empty);

        $noLogic2 = [];
        $empty2 = [&$noLogic2];
        $emptyContext2 = new Context($empty2);

        $factory->parse($emptyContext);
        $factory->parse($emptyContext2);

        $this->assertInstanceOf(NoLogic::class, $noLogic);
        $this->assertInstanceOf(NoLogic::class, $noLogic2);

        $this->assertTrue($noLogic->parse($emptyContext));
        $this->assertTrue($noLogic2->parse($emptyContext));

        // No singletons
        $this->assertNotSame($noLogic, $noLogic2);
    }

    public function testBuilderSetters()
    {
        $content = [
            'setter' => [
                'class' => \FactoryTestSetters::class,
                'arguments' => [],
                'setters' => [
                    'setter' => 'setData'
                ]
            ]
        ];

        $data = [&$content];
        $context = new Context($data);

        $factories = new Factories;

        $factories->getStory()->parse($context);

        /** @var Factory $factory */
        $factory = $content['setter'];
        unset($content, $context);

        $setData = [
            'setter' => 'test-answer'
        ];
        $content = [&$setData];
        $context = new Context($content);

        $factory->parse($context);

        $this->assertInstanceOf(\FactoryTestSetters::class, $setData);
        $this->assertSame('test-answer', $setData->data);
    }

    public function testBuilderSingleton()
    {
        $content = [
            'first' => [
                'class' => NoLogic::class,
                'arguments' => [
                    'return' => true
                ],
                'singleton' => true
            ]
        ];

        $data = [&$content];
        $context = new Context($data);

        $factories = new Factories;

        $factories->getStory()->parse($context);

        /** @var Factory $factory */
        $factory = $content['first'];

        $this->assertInstanceOf(Factory::class, $factory);

        $noLogic = [];
        $empty = [&$noLogic];
        $emptyContext = new Context($empty);

        $noLogic2 = [];
        $empty2 = [&$noLogic2];
        $emptyContext2 = new Context($empty2);

        $factory->parse($emptyContext);
        $factory->parse($emptyContext2);

        $this->assertInstanceOf(NoLogic::class, $noLogic);
        $this->assertInstanceOf(NoLogic::class, $noLogic2);

        $this->assertTrue($noLogic->parse($emptyContext));
        $this->assertTrue($noLogic2->parse($emptyContext));

        // Singletons
        $this->assertSame($noLogic, $noLogic2);
    }


}
