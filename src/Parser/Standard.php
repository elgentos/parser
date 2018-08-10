<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 0:54
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Interfaces\ParserInterface;
use Elgentos\Parser\Stories\Factory;

class Standard implements ParserInterface
{

    /**
     * @inheritdoc
     */
    public function parse(array &$data, string $storyCode, ...$arguments)//: void
    {
        $context = new Context($data);

        (Factory::create($storyCode, ...$arguments))
                ->getStory()
                ->parse($context);
    }

}
