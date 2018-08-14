<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-8-18
 * Time: 12:34
 */

namespace Elgentos\Parser\Matcher;

use PHPUnit\Framework\TestCase;

abstract class CoreTestAbstract extends TestCase
{

    /**
     * @dataProvider dataProvider
     */
    public function testExecute(CoreAbstract $abstract, bool $result, $haystack, string $message = null)
    {
        $message = $message ?? sprintf('%s->execute(%s)', get_class($abstract), $haystack);
        $this->assertSame($result, $abstract->execute($haystack), $message);
    }

    abstract public function dataProvider(): array;

}
