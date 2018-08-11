<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 13-7-18
 * Time: 9:01
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Interfaces\RuleInterface;

abstract class FileAbstract implements RuleInterface
{

    /**
     * Filter nasty strings from path
     *
     * @param string $path
     * @return string
     */
    private function safePath(string $path): string
    {
        while (($newPath = str_replace(['..', '//'], ['', '/'], $path)) !== $path) {
            $path = $newPath;
        }

        return str_replace(['..', '//'], ['', '/'], $path);
    }

    /**
     * Get file path
     *
     * @param string $path
     * @return string
     */
    protected function getSafepath(string $path): string
    {
        return $this->safePath($path);
    }

}
