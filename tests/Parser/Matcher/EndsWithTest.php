<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 11:19
 */

namespace Elgentos\Parser\Matcher;

class EndsWithTest extends CoreTestAbstract
{

    public function dataProvider(): array
    {
        $endsWith = new EndsWith('test');

        return [
                [$endsWith, false, 'somethingselse'],
                [$endsWith, false, 'testing'],
                [$endsWith, false, 'Testing'],
                [$endsWith, false, 'isTest'],
                [$endsWith, true, 'is_test'],
        ];
    }

}
