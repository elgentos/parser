<?php
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-12-18
 * Time: 8:51
 */

namespace Elgentos\Parser\Stories\Reader;

use Elgentos\Parser\Story;
use Elgentos\Parser\Interfaces\RuleInterface;

class Simple extends Complex
{
    protected function initStory(string $rootDir): Story
    {
        return $this->getMetrics()->createStory(
            '0-root',
            $this->filesStory($rootDir),
            $this->finalStory()
        );
    }

    protected function filesStory(string $rootDir): RuleInterface
    {
        return $this->getMetrics()->createStory(
            '1-files', $this->importStory($rootDir, true)
        );
    }
}
