<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-12-18
 * Time: 21:33
 */

namespace Elgentos\Parser\Stories\Builder;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\GeneralException;
use Elgentos\Parser\Rule\Factory;
use Elgentos\Parser\Rule\Iterate;
use Elgentos\Parser\Rule\NoLogic;
use Elgentos\Parser\Story;
use Elgentos\Parser\StoryMetrics;
use PHPUnit\Framework\TestCase;

class StructureTest extends TestCase
{

    public function testGetMetrics()
    {
        $structures = new Structure;
        $this->assertInstanceOf(StoryMetrics::class, $structures->getMetrics());
    }

    public function testGetStory()
    {
        $structures = new Structure;
        $this->assertInstanceOf(Story::class, $structures->getStory());
    }

    public function testConstructurWithFactories()
    {
        $factories = [
            'factory1' => new Factory(NoLogic::class, [true]),
            'factory2' => new Factory(NoLogic::class, [false]),
            'factory3' => new \stdClass,
        ];

        $this->expectException(\TypeError::class);
        new Structure($factories);
    }

    public function testStoryMetrics()
    {
        $structures = new Structure;

        $metrics = $structures->getMetrics()
                ->getStatistics('%s');
        $this->assertSame([
                '0-root',
                '1-builder'
        ], $metrics);
    }

    public function testStructure()
    {
        $factories = [
            'stdClass' => new Factory(\stdClass::class)
        ];

        $structures = new Structure($factories);

        /** @var Story $template */
        $template = [
            /**
             * factory is a defined class
             */
            'factory' => [
                'class' => Story::class,
                /**
                 * default arguments
                 */
                'arguments' => [
                    'name' => 'test-story'
                ]
            ],
            'children' => [
                'iterate' => [
                    'factory' => [
                        'class' => Iterate::class,
                        'arguments' => [
                            'recursive' => false,
                        ]
                    ],
                    'multiple' => true,
                ],
                /**
                 * this is a lookup factory defined in factories
                 */
                'standard' => [
                    'factory' => 'stdClass',
                ]
            ]
        ];
        $content = ['@template' => &$template];

        $context = new Context($content);
        $structures->getStory()->parse($context);

        $this->assertInstanceOf(Story::class, $template);
        /**
         * all childs should be a page before the actual root
         */
        $this->assertSame(3, $template->getPages());
    }

    public function testUndefinedFactory()
    {
        $factories = [
            'stdClass' => new Factory(\stdClass::class)
        ];

        $this->expectException(GeneralException::class);

        $template = [
            'factory' => 'stdClass',
            'children' => [
                'standard' => [
                    'factory' => 'non-existent'
                ]
            ],
        ];

        $content = [&$template];
        $context = new Context($content);


        $structures = new Structure($factories);

        $structures->getStory()
                ->parse($context);
    }

}
