<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: jeroen
 * Date: 12-7-18
 * Time: 14:20
 */

namespace Elgentos\Parser\Rule;

use Elgentos\Parser\Context;
use Elgentos\Parser\Exceptions\GeneralException;
use Elgentos\Parser\Exceptions\RuleInvalidContextException;
use Elgentos\Parser\Interfaces\RuleInterface;

class Filter implements RuleInterface
{

    /** @var string */
    private $pathSeparator;

    public function __construct(string $pathSeparator)
    {
        $this->pathSeparator = $pathSeparator;
    }

    public function parse(Context $context): bool
    {
        $filter = $context->getCurrent();
        $root = &$context->getRoot();
        if (! \is_array($filter)) {
            throw new RuleInvalidContextException(sprintf("%s expects a array", self::class));
        }

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

        $root = \array_filter($root, function(&$row) use ($index, $values, $inverse) {
            if (! \is_array($row)) {
                return true;
            }

            if (! isset($row[$index])) {
                return true;
            }

            return (\array_search($row[$index], $values) !== false) !== $inverse;
        });

        return true;
    }

}
