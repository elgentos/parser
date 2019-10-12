<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 11:19
 */

namespace Elgentos\Parser\Matcher;

class BeginsWithTest extends CoreTestAbstract
{
    public function dataProvider(): array
    {
        $beginsWith = new BeginsWith('test');

        return [
                [$beginsWith, false, 'somethingselse'],
                [$beginsWith, true, 'testing'],
                [$beginsWith, false, 'Testing'],
                [$beginsWith, false, 'isTest'],
                [$beginsWith, false, 'is_test'],
        ];
    }
}
