<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 20:54
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Exceptions\ArrayTooSmallException;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsArray;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\Matcher\MatchAll;
use Dutchlabelshop\Parser\RuleAbstract;

class Csv extends RuleAbstract
{
    /** @var bool */
    private $firstHasKeys;
    /** @var MatcherInterface */
    private $matcher;
    /** @var string */
    private $delimiter;
    /** @var string */
    private $enclosure;
    /** @var string */
    private $escape;

    public function __construct(
            bool $firstHasKeys = false,
            MatcherInterface $matcher = null,
            string $delimiter = ',',
            string $enclosure = '"',
            string $escape = "\\"
    ) {
        $this->firstHasKeys = $firstHasKeys;
        $this->matcher = $matcher ?? new MatchAll(
                new IsExact('__csv'),
                new IsArray
        );
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    public function parse(Context $context): bool
    {
        if (! $this->match($context)) {
            return false;
        }
        $context->changed();

        $current = &$context->getCurrent();

        if (empty($current)) {
            return true;
        }

        if ($this->firstHasKeys && count($current) < 2) {
            return true;
        }

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
