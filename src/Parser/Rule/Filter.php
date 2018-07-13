<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 14:20
 */

namespace Dutchlabelshop\Parser\Rule;

use Dutchlabelshop\Parser\Context;
use Dutchlabelshop\Parser\Exceptions\GeneralException;
use Dutchlabelshop\Parser\Interfaces\MatcherInterface;
use Dutchlabelshop\Parser\Matcher\IsArray;
use Dutchlabelshop\Parser\Matcher\IsExact;
use Dutchlabelshop\Parser\Matcher\MatchAll;
use Dutchlabelshop\Parser\RuleAbstract;

class Filter extends RuleAbstract
{

    /** @var string */
    private $pathSeparator;
    /** @var MatcherInterface */
    private $matcher;

    public function __construct(string $pathSeparator = '/', MatcherInterface $matcher = null)
    {
        $this->pathSeparator = $pathSeparator;
        $this->matcher = new MatchAll(
                $matcher ?? new IsExact('__filter'),
                    new IsArray()
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

        $path = (string)($filter['path'] ?? '0');

        try {
            $subContext = $context->search($path, $this->pathSeparator);
        } catch (GeneralException $e) {
            return false;
        }

        $this->filter(
                $subContext,
                (string)($filter['index'] ?? ''),
                (bool)($filter['inverse'] ?? false),
                ... (array)($filter['value'] ?? '')
        );
        $context->changed();

        return true;
    }

    private function filter(Context $context, string $index, bool $inverse = false, string ...$values): bool
    {
        $root = &$context->getRoot();

        $root = array_filter($root, function(&$row) use ($index, $values, $inverse) {
            if (! is_array($row)) {
                return true;
            }

            if (! isset($row[$index])) {
                return true;
            }

            return (array_search($row[$index], $values) !== false) !== $inverse;
        });

        return true;
    }

}
