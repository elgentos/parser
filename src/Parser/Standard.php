<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 0:54.
 */

namespace Elgentos\Parser;

use Elgentos\Parser\Interfaces\ParserInterface;
use Elgentos\Parser\Interfaces\StoriesInterface;

class Standard implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(array &$data, StoriesInterface $stories): void
    {
        $context = new Context($data);
        $stories->getStory()
            ->parse($context);
    }
}
