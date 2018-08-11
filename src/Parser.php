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

        $parser = $parser ?? new Standard;
        $parser->parse($data, 'reader', $rootDir);
        return $data;
    }

}
