<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 16-7-18
 * Time: 10:15
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use PHPUnit\Framework\TestCase;

class MergeUpTest extends TestCase
{

    public function testMerge()
    {
        $root = [
                'merge' => [
                        'test' => 'overwrite',
                        'recursive' => [
                                ['overwrite']
                        ]
                ],
                'test' => 'content',
                'recursive' => [
                        ['content']
                ]
        ];
        $context = new Context($root);

        $rule = new MergeUp(true);

        $test = [
                'test' => 'overwrite',
                'recursive' => [
                        ['overwrite']
                ]
        ];

        $rule->execute($context);
        $this->assertSame($test, $context->getRoot());
    }

}
