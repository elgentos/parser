<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 17-7-18
 * Time: 12:05
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Matcher\IsFalse;
use Elgentos\Parser\Matcher\IsTrue;
use PHPUnit\Framework\TestCase;

class GlobTest extends TestCase
{

    const DATAPATH = __DIR__ . '/data';

    /** @var Context */
    private $context;
    /** @var array */
    private $files;

    public function setUp()
    {
        $root = [
                'files' => '.'
        ];
        $this->context = new Context($root);

        $this->files = array_map(
                function($file) {
                    return './' . basename($file);
                },
                glob(self::DATAPATH . DIRECTORY_SEPARATOR . '*')
        );
        sort($this->files, SORT_STRING | SORT_NATURAL);
    }

    public function testExecute()
    {
        $root = [
                'path' => '.'
        ];
        $context = new Context($root);

        $rule = new Glob(self::DATAPATH);

        $this->assertTrue($rule->execute($context));
        $this->assertSame($this->files, $context->getCurrent());
        $this->assertTrue($context->isChanged());
    }

    public function testGetMatcher()
    {
        $rule = new Glob(self::DATAPATH);
        $this->assertInstanceOf(IsTrue::class, $rule->getMatcher());

        $rule = new Glob(self::DATAPATH, new IsFalse);
        $this->assertInstanceOf(IsFalse::class, $rule->getMatcher());
    }

}
