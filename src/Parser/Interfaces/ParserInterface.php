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
     * @param string $storyCode
     * @param mixed ...$arguments
     * @return void
     */
    public function parse(array &$data, string $storyCode, ...$arguments);//: void;

}
