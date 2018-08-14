<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:28
 */

namespace Elgentos\Parser\Matcher;

class RegExpTest extends CoreTestAbstract
{

    public function dataProvider(): array
    {
        $regExp = new RegExp("#^\d+$#");

        return [
            [$regExp, false, 'test'],
            [$regExp, true, '132'],
        ];
    }

}
