<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 12:34
 */

namespace Dutchlabelshop\Parser\Matcher;


use Dutchlabelshop\Parser\Context;
use PHPUnit\Framework\TestCase;

class MatcherAbstract extends TestCase
{
    /** @var Context */
    protected $context;

    public function setUp()
    {
        $root = [];
        $this->context = new Context($root);
    }

}
