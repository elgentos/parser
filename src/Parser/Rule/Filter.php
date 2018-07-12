<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 14:20
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\CurrentIsArray;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\Matcher\MatchAll;
use Dutchlabelshop\Parser\RuleAbstract;

class Filter extends RuleAbstract
{

    private $matcher;

    public function __construct(MatcherInterface $matcher = null)
    {
        $this->matcher = new MatchAll(
                $matcher ?? new IsExact('__filter'),
                    new CurrentIsArray()
        );
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

        $filter = $context->getCurrent();
        $root = &$context->getRoot();
        unset($root[$context->getIndex()]);

        if (! is_array($filter)) {
            return false;
        }

        if (! $this->filter(
                $context,
                (string)($filter['path'] ?? '0'),
                (string)($filter['index'] ?? ''),
                ... (array)($filter['value'] ?? '')
        )) {
            return false;
        }

        return $this->executeRule($context);
    }

    private function filter(Context $context, string $path, string $index, string ...$values): bool
    {
        $root = &$context->getRoot();

        $path = explode('/', $path);

        foreach ($path as $p) {
            if (! isset($root[$p])) {
                return false;
            }

            $root = &$root[$p];
        }

        if (! is_array($root)) {
            return false;
        }

        $root = array_filter($root, function(&$row) use ($index, $values) {
            if (! is_array($row)) {
                return true;
            }

            if (! isset($row[$index])) {
                return true;
            }

            return array_search($row[$index], $values) !== false;
        });

        return true;
    }

}
