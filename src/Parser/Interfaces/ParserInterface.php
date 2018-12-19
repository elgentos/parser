<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 11:06
 */

namespace Elgentos\Parser\Interfaces;


interface ParserInterface
{

    /**
     * Parse data with Factory
     *
     * @param array $data
     * @param StoriesInterface $stories
     */
    public function parse(array &$data, StoriesInterface $stories): void;

}
