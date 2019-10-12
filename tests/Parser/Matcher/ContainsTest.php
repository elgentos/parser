<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 0:19.
 */

namespace Elgentos\Parser\Matcher;

class ContainsTest extends CoreTestAbstract
{
    public function dataProvider(): array
    {
        $containsCaseSensitive = new Contains('test');

        return [
                [$containsCaseSensitive, false, 'somethingselse'],
                [$containsCaseSensitive, true, 'testing'],
                [$containsCaseSensitive, false, 'Testing'],
                [$containsCaseSensitive, false, 'isTest'],
                [$containsCaseSensitive, true, 'is_test'],
        ];
    }
}
