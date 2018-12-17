<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 10-8-18
 * Time: 11:45
 */

namespace Elgentos;

use Elgentos\Parser\Interfaces\ParserInterface;
use Elgentos\Parser\Standard;
use Elgentos\Parser\Stories\Reader\Complex;
use Elgentos\Parser\Stories\Reader\Simple;

class Parser
{

    /**
     * Read a file in a given basedir
     * defaults to current workdir
     *
     * optional, give a own parser if you want to debug
     *
     * @param string $filename
     * @param string $rootDir
     * @param ParserInterface|null $parser
     * @return array
     */
    public static function readFile(string $filename, string $rootDir = '.', ParserInterface $parser = null): array
    {
        $data = ['@import' => $filename];

        $story = new Complex($rootDir);
        $parser = $parser ?? new Standard;

        $parser->parse($data, $story);
        return $data;
    }

    /**
     * Read a file without recursion
     *
     * @param string $filename
     * @param string $rootDir
     * @param ParserInterface|null $parser
     * @return array
     */
    public static function readSimple(string $filename, string $rootDir = '.', ParserInterface $parser = null): array
    {
        $data = ['@import' => $filename];

        $story = new Simple($rootDir);
        $parser = $parser ?? new Standard;

        $parser->parse($data, $story);
        return $data;
    }

}
