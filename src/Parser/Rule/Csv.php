<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 20:54.
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Interfaces\RuleInterface;

class Csv implements RuleInterface
{
    const DEFAULT_DELIMITER = ',';
    const DEFAULT_ENCLOSURE = '"';
    const DEFAULT_ESCAPE = '\\';

    /** @var bool */
    private $firstHasKeys;
    /** @var string */
    private $delimiter;
    /** @var string */
    private $enclosure;
    /** @var string */
    private $escape;

    public function __construct(
            bool $firstHasKeys = false,
            string $delimiter = self::DEFAULT_DELIMITER,
            string $enclosure = self::DEFAULT_ENCLOSURE,
            string $escape = self::DEFAULT_ESCAPE
    ) {
        $this->firstHasKeys = $firstHasKeys;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    public function parse(Context $context): bool
    {
        $current = &$context->getCurrent();

        if (empty($current)) {
            return false;
        }
        if (!\is_array($current)) {
            throw new RuleInvalidContextException(sprintf('%s expects a array', self::class));
        }

        if ($this->firstHasKeys && \count($current) < 2) {
            return false;
        }
        $context->changed();

        $length = [];
        $current = \array_map(function (string $line) use (&$length) {
            $result = \str_getcsv(
                    $line,
                    $this->delimiter,
                    $this->enclosure,
                    $this->escape
            );

            $length[] = \count($result);

            return $result;
        }, $current);

        if (!$this->firstHasKeys) {
            return true;
        }

        $keys = \array_shift($current);
        $longest = \max(...$length);
        $numkeys = \array_shift($length);

        if ($numkeys < $longest) {
            $keys = \array_merge($keys, \range($numkeys, $longest - 1));
        }

        $current = \array_map(function ($line, $length) use (&$keys, &$longest) {
            if ($length < $longest) {
                $line = \array_merge(
                        $line,
                        \array_fill(0, $longest - $length, null)
                );
            }

            return \array_combine($keys, $line);
        }, $current, $length);

        return true;
    }
}
