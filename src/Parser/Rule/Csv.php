<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 14-7-18
 * Time: 20:54
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Interfaces\RuleInterface;

class Csv implements RuleInterface
{
    const DEFAULT_DELIMITER = ',';
    const DEFAULT_ENCLOSURE = '"';
    const DEFAULT_ESCAPE = "\\";

    const QUOTED_CHAR = '!!Q!!';
    const STRING_ENCODED = '!!ENC!!';

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
        if (! \is_string($current)) {
            throw new RuleInvalidContextException(sprintf("%s expects a string", self::class));
        }

        // Explode with escaped lines
        $quotedEnclosure = preg_quote($this->enclosure, '/');

        $current = explode(
            "\n",
            preg_replace_callback(
                '/' . $quotedEnclosure . '(.*?)' . $quotedEnclosure . '/s',
                static function($field) {
                    return self::STRING_ENCODED . urlencode(utf8_encode($field[1]));
                },
                preg_replace(
                    '/(?<!' . $quotedEnclosure . ')' . preg_quote($this->escape, '/') . '/',
                    self::QUOTED_CHAR,
                    $current
                )
            )
        );

        if ($this->firstHasKeys && \count($current) < 2) {
            return false;
        }
        $context->changed();

        $length     = [];
        $enclosure  = &$this->enclosure;
        $delimiter  = &$this->delimiter;
        $escape     = &$this->escape;
        $current = \array_map(function (string $line) use (&$length, &$enclosure, &$delimiter, &$escape) {

            $result = \str_getcsv(
                    $line,
                    $delimiter,
                    $enclosure,
                    $escape
            );

            $length[] = \count($result);
            return array_map(static function($field) use (&$enclosure) {
                if (strpos($field, self::STRING_ENCODED) === false) {
                    return $field;
                }

                return str_replace([self::STRING_ENCODED, self::QUOTED_CHAR], ['', $enclosure], utf8_decode(urldecode($field)));
            }, $result);
        }, $current);

        if (! $this->firstHasKeys) {
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
