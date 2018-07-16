<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 14:50
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Matcher\IsArray;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{

    public function testMatch()
    {
        $root = [
                '__filter' => [
                        // rules
                ]
        ];
        $context = new Context($root);

        $current = &$context->getCurrent();

        $rule = new Filter('/');

        $context->setIndex('test');
        $this->assertFalse($rule->match($context));

        $context->setIndex('__filter');
        $this->assertTrue($rule->match($context));

        $current = '';
        $this->assertFalse($rule->match($context));
    }

    public function testParseShouldRemoveSelf()
    {
        $root = [
                '__filter' => []
        ];
        $context = new Context($root);
        $rule = new Filter('/');

        $rule->execute($context);
        $this->assertSame([], $context->getRoot());
    }

    public function testFilterOneValue()
    {
        $root = $test = [
                'test' => [
                    [],
                    [
                            'values' => [
                                [
                                        'key' => 'test',
                                ],
                                'skip' => 'me',
                                [
                                        'key' => 'blah',
                                ],
                            ]
                    ],
                    [
                            'values' => [
                                    [
                                            'key' => 'test2',
                                    ],
                                    [
                                            'key' => 'blah2',
                                    ],
                            ]

                    ]
                ],
        ];

        // Test filter 1 one value
        $root['__filter'] = [
                'path' => 'test/1/values',
                'index' => 'key',
                'value' => 'test'
        ];
        unset($test['test'][1]['values'][1]);

        $context = new Context($root);
        $context->setIndex('__filter');

        $rule = new Filter('/');

        $this->assertTrue($rule->execute($context));
        $this->assertTrue($context->isChanged());
        $this->assertSame($test, $context->getRoot());

        // Test filter 2 values which is no array
        $root['__filter'] = [
                'path' => 'test/1/values/skip',
                'index' => 'key',
                'value' => ['test2', 'blah2']
        ];

        $this->assertFalse($rule->execute($context));
        $this->assertSame($test, $context->getRoot());

        // Test filter 3 filter values with same result
        $root['__filter'] = [
                'path' => 'test/2/values',
                'index' => 'key',
                'value' => ['test2', 'blah2']
        ];

        $this->assertTrue($rule->execute($context));
        $this->assertSame($test, $context->getRoot());

        // Test filter 4 no array
        $root['__filter'] = [
                'path' => 'test/2/values',
                'index' => 'nonexistant',
                'value' => ['test2', 'blah2']
        ];

        $this->assertTrue($rule->execute($context));
        $this->assertSame($test, $context->getRoot());

        // Test filter 5 inverse filter
        $root['__filter'] = [
                'path' => 'test/2/values',
                'index' => 'key',
                'value' => 'test2',
                'inverse' => true
        ];

        unset($test['test'][2]['values'][0]);

        $this->assertTrue($rule->execute($context));
        $this->assertSame($test, $context->getRoot());
    }

    public function testDotPathSeperator()
    {
        $root = $test = [
                'path' => [
                        'to' => [
                                'values' => [
                                        [
                                                'key' => 'test'
                                        ],
                                        [
                                                'key' => 'remove'
                                        ],
                                ]
                        ]
                ],
                '__filter' => [
                        'path' => 'path.to.values',
                        'index' => 'key',
                        'value' => 'test'
                ]
        ];
        $context = new Context($root);

        $rule = new Filter('.');

        $context->setIndex('__filter');
        $this->assertTrue($rule->execute($context));
        $this->assertNotSame($test, $root);
        $this->assertTrue($context->isChanged());
    }

    public function testDefaultMatcher()
    {
        $rule = new Filter('/');
        $matcher = $rule->getMatcher();

        $this->assertInstanceOf(IsArray::class, $matcher);
    }

}
