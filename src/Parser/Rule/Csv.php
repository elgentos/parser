<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 20:54
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsArray;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\Matcher\MatchAll;

class Csv extends RuleAbstract
{
    /** @var MatcherInterface */
    private $matcher;
    /** @var bool */
    private $firstHasKeys;
    /** @var string */
    private $delimiter;
    /** @var string */
    private $enclosure;
    /** @var string */
    private $escape;

    public function __construct(
            MatcherInterface $matcher = null,
            bool $firstHasKeys = false,
            string $delimiter = ',',
            string $enclosure = '"',
            string $escape = "\\"
    ) {
        $this->matcher = $matcher ?? new MatchAll(
                new IsExact('__csv'),
                new IsArray
        );
        $this->firstHasKeys = $firstHasKeys;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function execute(Context $context): bool
    {
        $current = &$context->getCurrent();

        if (empty($current)) {
            return false;
        }

        if ($this->firstHasKeys && count($current) < 2) {
            return false;
        }
        $context->changed();

        $length = [];
        $current = array_map(function(string $line) use (&$length) {
            $result = str_getcsv(
                    $line,
                    $this->delimiter,
                    $this->enclosure,
                    $this->escape
            );

            $length[] = count($result);
            return $result;
        }, $current);

        if (! $this->firstHasKeys) {
            return true;
        }

        $keys = array_shift($current);
        $numkeys = array_shift($length);
        $longest = max(...$length);

        if ($numkeys < $longest) {
            $keys = array_merge($keys, range($numkeys, $longest - 1));
        }

        $current = array_map(function($line, $length) use (&$keys, &$longest) {
            if ($length < $longest) {
                $line = array_merge(
                        $line,
                        array_fill(0, $longest - $length, null)
                );
            }
            return array_combine($keys, $line);
        }, $current, $length);

        return true;
    }

}
